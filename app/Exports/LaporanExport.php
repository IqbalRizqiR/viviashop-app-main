<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function getReportsData($awal, $akhir)
    {
        $no = 1;
        $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;
        $total_pembelian_seluruh = 0;
        $total_penjualan_seluruh = 0;
        $total_pengeluaran_seluruh = 0;

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $total_penjualan = Order::where('created_at', 'LIKE', "%$tanggal%")->orWhere('created_at', 'LIKE', "%$tanggal%")->sum('grand_total');
            $total_pembelian = Pembelian::where('waktu', 'LIKE', "%$tanggal%")->orWhere('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal%")->sum('nominal');

            $pendapatan = $total_penjualan;
            $total_pendapatan += $pendapatan;

            $total_pembelian_seluruh += $total_pembelian;
            $total_penjualan_seluruh += $total_penjualan;
            $total_pengeluaran_seluruh += $total_pengeluaran;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['penjualan'] = format_uang($total_penjualan);
            $row['pembelian'] = format_uang($total_pembelian);
            $row['pengeluaran'] = format_uang($total_pengeluaran);
            $row['pendapatan'] = format_uang($pendapatan);

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penjualan' => format_uang($total_penjualan_seluruh),
            'pembelian' => format_uang($total_pembelian_seluruh),
            'pengeluaran' => format_uang($total_pengeluaran_seluruh),
            'pendapatan' => format_uang($total_pendapatan),
        ];

        return $data;


    }
    public function view(): View
    {
        $date1 = new Carbon('first day of this month');
        $date2 = new Carbon('last day of this month');
        $awal = $date1->toDateString();
        $akhir = $date2->toDateString();
        $data = $this->getReportsData($awal, $akhir);
        return view('admin.exports.laporan', [
            'laporan' => $data,
        ]);
    }
}
