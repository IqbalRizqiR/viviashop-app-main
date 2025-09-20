<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\RekamanStok;
use App\Models\Supplier;
use App\Services\StockService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PembelianController extends Controller
{
    public function index()
    {
        $supplier = Supplier::orderBy('nama')->get();

        return view('admin.pembelian.index', compact('supplier'));
    }

    public function invoices($id)
    {
        $pembelian = Pembelian::with(['supplier', 'details'])->where('id', $id)->first();
        $pembelian_detail = PembelianDetail::with(['products'])->where('id_pembelian', $id)->get();
        // dd($pembelian_detail);

        $pdf  = Pdf::loadView('admin.orders.invoicesBeli', compact('pembelian', 'pembelian_detail'))->setOptions(['defaultFont' => 'sans-serif']);
        $customPaper = array(0, 0, (80 * 2.83), 30000);
        $pdf->setPaper($customPaper);
        return $pdf->stream('invoice.pdf');
    }

    public function data()
    {
        $pembelian = Pembelian::orderBy('id', 'desc')->get();
        // dd($pembelian);

        return datatables()
            ->of($pembelian)
            ->addIndexColumn()
            ->addColumn('total_item', function ($pembelian) {
                return format_uang($pembelian->total_item);
            })
            ->addColumn('total_harga', function ($pembelian) {
                return 'Rp. '. format_uang($pembelian->total_harga);
            })
            ->addColumn('bayar', function ($pembelian) {
                return 'Rp. '. format_uang($pembelian->bayar);
            })
            ->addColumn('tanggal', function ($pembelian) {
                return tanggal_indonesia($pembelian->created_at, false);
            })
            ->addColumn('waktu', function ($pembelian) {
                return tanggal_indonesia(($pembelian->waktu != NULL ? $pembelian->waktu : $pembelian->created_at), false);
            })
            ->addColumn('supplier', function ($pembelian) {
                return $pembelian->supplier->nama;
            })
            ->editColumn('diskon', function ($pembelian) {
                return $pembelian->diskon . '%';
            })
            ->addColumn('aksi', function ($pembelian) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('admin.pembelian.show', $pembelian->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <a href="'. route('admin.pembelian.invoices', $pembelian->id) . '" class="btn btn-xs btn-info btn-flat"><i class="fa fa-download"></i></a>
                    <a href="'. route('admin.pembelian_detail.editBayar', $pembelian->id) .'" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></a>
                    <button onclick="deleteData(`'. route('admin.pembelian.destroy', $pembelian->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create($id)
    {
        $pembelian = new Pembelian();
        $pembelian->id_supplier = $id;
        $pembelian->total_item  = 0;
        $pembelian->total_harga = 0;
        $pembelian->diskon      = 0;
        $pembelian->bayar       = 0;
        $pembelian->status      = 'pending';
        $pembelian->waktu       = null;
        $pembelian->save();

        session(['id_pembelian' => $pembelian->id]);
        session(['id_supplier' => $pembelian->id_supplier]);

        return redirect()->route('admin.pembelian_detail.index');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $pembelian = Pembelian::with(['details', 'supplier'])->findOrFail($request->id_pembelian);
            $pembelian->total_item = $request->total_item;
            $pembelian->total_harga = $request->total;
            $pembelian->diskon = $request->diskon;
            $pembelian->bayar = $request->bayar;
            $pembelian->waktu = $request->waktu ?? Carbon::now();
            $pembelian->status = 'completed';
            $pembelian->payment_method = $request->payment_method ?? 'cash';
            $pembelian->notes = $request->notes;
            $pembelian->update();

            // Process stock updates using StockService for proper tracking
            StockService::processPurchaseStockUpdate($pembelian);

            // Note: Removed duplicate manual stock update logic
            // StockService now handles both simple and variant products correctly
            // with proper StockMovement record creation

            DB::commit();
            Alert::success('Data berhasil', 'Data pembelian berhasil disimpan dan stok telah diperbarui!');
            return redirect()->route('admin.pembelian.index');
            
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $pembelian = Pembelian::findOrFail($request->id_pembelian);
        $pembelian->total_item = $request->total_item;
        $pembelian->total_harga = $request->total;
        $pembelian->diskon = $request->diskon;
        $pembelian->bayar = $request->bayar;
        if ($request->waktu != NULL) {
            $pembelian->waktu = $request->waktu;
        }

        $pembelian->update();


        return redirect()->route('pembelian.index');
    }

    public function updateHargaBeli(Request $request, $id)
    {
        $produk = Product::where('id', $id)->first();
        $produk->update([
            'harga_beli' => $request->harga_beli
        ]);
        // $id_pembelian = $request->id;
        $detail = PembelianDetail::where('id', $request->id_pembayaran_detail)->first();
        // dd($request->all());
        // dd($request->jumlah);
        $jumlah = (int)$request->jumlah;
        $detail->update([
            'jumlah' => $jumlah,
            'harga_beli' => $produk->harga_beli,
            'subtotal' => $produk->harga_beli * $jumlah,
        ]);
    }
    public function updateHargaJual(Request $request, $id)
    {
        $produk = Product::where('id', $id)->first();
        $produk->update([
            'price' => $request->harga_jual
        ]);
        $detail = PembelianDetail::find($request->id);
        if ($request->jumlah == NULL || $request->jumlah == 0) {
            $detail->jumlah = 0;
            $detail->subtotal = $detail->harga_beli * $request->jumlah;
        } else {
            $detail->jumlah = $request->jumlah;
            $detail->subtotal = $detail->harga_beli * $request->jumlah;
        }
        $detail->update();
    }

    public function show($id)
    {
        $detail = PembelianDetail::with('products')->where('id_pembelian', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->products->id .'</span>';
            })
            ->addColumn('name', function ($detail) {
                return $detail->products->name;
            })
            ->addColumn('harga_beli', function ($detail) {
                return 'Rp. '. format_uang($detail->harga_beli);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::find($id);

        $pembelian->delete();

        return response(null, 204);
    }
}
