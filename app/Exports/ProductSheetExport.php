<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ProductSheetExport implements FromCollection, WithHeadings, WithTitle, WithEvents
{
    public function collection()
    {
        // Bisa kosong, karena hanya ingin buat template
        return collect([
            [
                'name' => '',
                'price' => '',
                'category_name' => '', // dropdown isi nama category
                'harga_beli' => '',
                'short_description' => '',
                'description' => '',
                'sku' => '',
                'weight' => '',
                'length' => '',
                'width' => '',
                'height' => '',
                'stok' => '',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'name',
            'price',
            'category_name', // dropdown isi nama category
            'harga_beli',
            'short_description',
            'description',
            'sku',
            'stok',
            'weight',
            'length',
            'width',
            'height',

        ];
    }

    public function title(): string
    {
        return 'Products';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Tambahkan dropdown kategori dari sheet Categories!A2:A100
                for ($row = 2; $row <= 100; $row++) {
                    $validation = new DataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setShowInputMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1('=Categories!$A$2:$A$100');

                    $sheet->getCell("C{$row}")->setDataValidation($validation);
                }
            }
        ];
    }
}
