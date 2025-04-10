<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Product;
use App\Models\RekamanStok;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PembelianController extends Controller
{
    public function index()
    {
        $supplier = Supplier::orderBy('nama')->get();

        return view('admin.pembelian.index', compact('supplier'));
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
        $pembelian->waktu       = Carbon::now();
        $pembelian->save();

        session(['id_pembelian' => $pembelian->id]);
        session(['id_supplier' => $pembelian->id_supplier]);

        return redirect()->route('admin.pembelian_detail.index');
    }

    public function store(Request $request)
    {
        $pembelian = Pembelian::findOrFail($request->id_pembelian);
        $pembelian->total_item = $request->total_item;
        $pembelian->total_harga = $request->total;
        $pembelian->diskon = $request->diskon;
        $pembelian->bayar = $request->bayar;
        $pembelian->waktu = $request->waktu;
        $pembelian->update();
        $detail_pembelian = Pembelian::where('id', $request->id_pembelian)->get();
        $detail = PembelianDetail::where('id_pembelian', $pembelian->id)->get();
        // dd($detail);
        $id_pembelian = $request->id_pembelian;
        // dd(count($detail));
        // dd(count($detail));
        if (count($detail) > 1) {
            foreach ($detail as $item) {
                $produk = Product::find($item->id_produk);
                $stok = $produk->productInventory->qty;
                // dd(count($item));
                RekamanStok::create([
                    'product_id' => $item->id_produk,
                    'waktu' => Carbon::now(),
                    'stok_masuk' => $item->jumlah,
                    'id_pembelian' => $id_pembelian,
                    'stok_awal' => $produk->stok,
                    'stok_sisa' => $stok += $item->jumlah,
                ]);
                $produk->productInventory->qty += $item->jumlah;
                $produk->productInventory->update();
                if ($produk) {
                    Alert::success('Data berhasil', 'Data berhasil di tambahkan!');
                    return redirect()->route('admin.pembelian.index');
                } else {
                    Alert::error('Data gagal', 'Data gagal di tambahkan!');
                    return redirect()->back();
                }

            }
        } elseif(count($detail) == 1) {
            $details = PembelianDetail::where('id_pembelian', $pembelian->id)->first();
            $cek = RekamanStok::where('id_pembelian', $id_pembelian)->get();

            if (count($cek) <= 0) {
                $produk = Product::find($details->id_produk);
                $stok = $produk->productInventory->qty;
                RekamanStok::create([
                    'product_id' => $details->id_produk,
                    'waktu' => Carbon::now(),
                    'stok_masuk' => $details->jumlah,
                    'id_pembelian' => $id_pembelian,
                    'stok_awal' => $produk->productInventory->qty,
                    'stok_sisa' => $stok += $details->jumlah,
                ]);
                $produk->productInventory->qty += $details->jumlah;
                $produk->productInventory->update();
                if ($produk) {
                    Alert::success('Data berhasil', 'Data berhasil di tambahkan!');
                    return redirect()->route('admin.pembelian.index');
                } else {
                    Alert::error('Data gagal', 'Data gagal di tambahkan!');
                    return redirect()->back();
                }
            } else {
                $produk = Product::find($details->id_produk);
                $stok = $produk->productInventory->qty;
                // dd($stok);
                $sums = $details->jumlah - $stok;
                if ($sums < 0 && $sums != 0) {
                    $sum = $sums * -1;
                } else {
                    $sum = $sums;
                }

                // dd($sum);
                $rekaman_stok = RekamanStok::where('id_pembelian', $pembelian->id)->first();
                $rekaman_stok->update([
                    'product_id' => $produk->id_produk,
                    'waktu' => Carbon::now(),
                    'stok_masuk' => $rekaman_stok->stok_masuk += $sum,
                    'stok_sisa' => $rekaman_stok->stok_sisa += $sum,
                ]);
                $produk->productInventory->qty += $sum;
                $produk->productInventory->update();
                if ($produk) {
                    Alert::success('Data berhasil', 'Data berhasil di tambahkan!');
                    return redirect()->route('admin.pembelian.index');
                } else {
                    Alert::error('Data gagal', 'Data gagal di tambahkan!');
                    return redirect()->back();
                }
            }
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
