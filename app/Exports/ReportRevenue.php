<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Http\Controllers\Frontend\HomepageController;

class ReportRevenue implements FromArray, WithHeadings, ShouldAutoSize
{
    protected array $data;

    public function __construct(string $awal, string $akhir)
    {
        // reuse your controller’s logic
        $ctrl = new HomepageController();
        $this->data = $ctrl->getReportsData($awal, $akhir);
    }

    public function array(): array
    {
        // map into pure rows
        return array_map(fn($row) => [
            $row['DT_RowIndex'],
            $row['tanggal'],
            $row['penjualan'],
            $row['pembelian'],
            $row['pengeluaran'],
            $row['pendapatan'],
            $row['keuntungan'],
        ], $this->data);
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Penjualan',
            'Pembelian',
            'Pengeluaran',
            'Pendapatan',
            'Keuntungan',
        ];
    }
}
