<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use App\Models\Category;

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
            BeforeExport::class => function (BeforeExport $event) {
                /** @var Worksheet $sheet */
                $sheet = $event->writer->getDelegate()->getActiveSheet();

                $categories = Category::pluck('name')->toArray();
                $list = implode(',', array_map(function ($v) {
                    return '"' . str_replace('"', '""', $v) . '"';
                }, $categories));

                // Tempelkan dropdown ke cell kolom C (category_id), baris 2-100 (misalnya)
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
            },
        ];
    }
}

?>
