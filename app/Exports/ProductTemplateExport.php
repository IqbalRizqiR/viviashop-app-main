<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

use App\Models\Category;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductTemplateExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        // Bisa kosong, karena hanya ingin buat template
        return collect([
            [
                'name' => '',
                'price' => '',
                'harga_beli' => '',
                'category_id' => '', // dropdown isi nama category
                'short_description' => '',
                'description' => '',
                'sku' => '',
                'stok' => '',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'name',
            'price',
            'harga_beli',
            'category_id', // dropdown isi nama category
            'short_description',
            'description',
            'sku',
            'stok',

        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $categories = Category::pluck('name')->toArray();

                // Handle tanda kutip agar tidak crash di formula Excel
                $list = implode(',', array_map(function ($v) {
                    return '"' . str_replace('"', '""', $v) . '"';
                }, $categories));

                // Set dropdown untuk baris 2-100 di kolom C (category_id)
                for ($row = 2; $row <= 100; $row++) {
                    $validation = $sheet->getCell("C$row")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1('"' . $list . '"');
                }
            }
        ];
    }
}

?>
