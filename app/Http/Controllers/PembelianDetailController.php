<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Product;
use App\Models\RekamanStok;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PembelianDetailController extends Controller
{
    public function index()
    {
        $id_pembelian = session('id_pembelian');
        $produk = Product::orderBy('name')->where('type', 'simple')->get();
        $supplier = Supplier::find(session('id_supplier'));
        $diskon = Pembelian::find($id_pembelian)->diskon ?? 0;

        if (! $supplier) {
            abort(404);
        }

        return view('admin.pembelian_detail.index', compact('id_pembelian', 'produk', 'supplier', 'diskon'));
    }

    public function editBayar($id)
    {
        $id_pembelian = $id;
        $produk = Product::orderBy('name')->get();
        $produk_supplier = Pembelian::where('id', $id)->first();
        $detail_pembelian = PembelianDetail::where('id_pembelian', $id)->get();
        $supplier = Supplier::find($produk_supplier->id_supplier);
        $diskon = Pembelian::find($id_pembelian)->diskon ?? 0;
        $tanggal = Pembelian::where('id', $id_pembelian)->first();

        if (! $supplier) {
            abort(404);
        }

        return view('admin.pembelian_detail.editBayar', compact('id_pembelian', 'tanggal', 'detail_pembelian', 'produk', 'supplier', 'diskon'));
    }

    public function data($id)
    {
        $detail = PembelianDetail::with('products')
            ->where('id_pembelian', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->products['id'] .'</span>';
            $row['nama_produk'] = $item->products['name'];
            $row['harga_jual']  = '<input type="number" class="form-control input-sm price" data-id="'. $item->products['id'] .'" value="'. $item->products['price'] .'">';
            $row['harga_beli']  = '<input type="number" class="form-control input-sm harga_beli" data-id="'. $item->products['id'] .'" data-uid="'. $item->id .'" value="'. $item->products['harga_beli'] .'">';
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id .'" value="'. $item->jumlah .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('admin.pembelian_detail.destroy', $item->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_beli * $item->jumlah;
            $total_item += $item->jumlah;
        }

        // Sembunyikan total dan total_item di baris terakhir jika ada data
        if (!empty($data)) {
            $data[0]['kode_produk'] = '
                <div class="total d-none">'. $total .'</div>
                <div class="total_item d-none">'. $total_item .'</div>
                <span class="label label-success">'. $data[0]['kode_produk'] .'</span>';
        }

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'nama_produk', 'jumlah', 'harga_beli', 'harga_jual'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Product::where('id', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new PembelianDetail();
        $detail->id_pembelian = $request->id_pembelian;
        if ($produk->harga_beli != null) {
            $detail->id_produk = $produk->id;
            $detail->harga_beli = $produk->harga_beli;
        } else {
            $detail->id_produk = $produk->id;
            $detail->harga_beli = 0;
        }

        $detail->jumlah = 0;
        $detail->subtotal = $produk->harga_beli * 0;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PembelianDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_beli * $request->jumlah;
        $detail->update();
        // dd($detail);
        if($detail) {
            $id = $detail->id_produk;
            $produk = Product::where('id', $id)->first();
            $stok = $produk->productInventory->qty;
            RekamanStok::create([
                'product_id' => $detail->id_produk,
                'waktu' => Carbon::now(),
                'stok_masuk' => $request->jumlah,
                'stok_awal' => $stok,
                'stok_sisa' => $produk->productInventory->qty += $request->jumlah,
            ]);
        }
    }

    public function updateEdit(Request $request, $id)
    {
        $detail = PembelianDetail::where('id', $id)->first();
        // dd($detail->id);
        // dd($detail->id_pembelian);
        // dd($request->jumlah);
        $rekaman_stok = RekamanStok::where('id_pembelian', $detail->id_pembelian)->where('id_produk', $detail->id_produk)->first();
        $cari = RekamanStok::where('id_pembelian', $detail->id_pembelian)->where('id_produk', $detail->id_produk)->first();
        if (!empty($rekaman_stok->id_produk) == $detail->id_produk) {
            $sum = $request->jumlah - $detail->jumlah;
            // dd($sum);
            if ($sum < 0 && $sum != 0) {
                $positive = $sum * -1;
                $id = $detail->id_produk;
                $produk = Product::where('id', $id)->first();
                $stok = $produk->productInventory->qty;
                $rekaman_stok->update([
                    'product_id' => $detail->id_produk,
                    'waktu' => Carbon::now(),
                    'stok_masuk' => $rekaman_stok->stok_masuk -= $positive,
                    'stok_awal' => $stok,
                    'stok_sisa' => $rekaman_stok->stok_sisa -= $positive,
                ]);
                $produk = Product::find($detail->id_produk);
                $produk->productInventory->qty -= $positive;
                $produk->update();
            } elseif($sum >= 1 && $sum != 0) {
                $id = $detail->id_produk;
                $produk = Product::where('id', $id)->first();
                $stok = $produk->productInventory->qty;
                $rekaman_stok->update([
                    'product_id' => $detail->id_produk,
                    'waktu' => Carbon::now(),
                    'stok_masuk' => $rekaman_stok->stok_masuk += $sum,
                    'stok_sisa' => $rekaman_stok->stok_sisa += $sum,
                    'stok_awal' => $stok,
                ]);
                $positive = $sum * -1;
                $produk = Product::find($detail->id_produk);
                $produk->productInventory->qty += $sum;
                $produk->update();
            }

        } else {
            $produk = Product::find($detail->id_produk);
            $sum = $request->jumlah;
            $stok = $produk->productInventory->qty;
            RekamanStok::create([
                'product_id' => $detail->id_produk,
                'waktu' => Carbon::now(),
                'stok_masuk' => $sum,
                'stok_awal' => $produk->productInventory->qty,
                'stok_sisa' => $stok += $sum,
            ]);
            $produk->productInventory->qty += $sum;
            $produk->update();
        }

        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_beli * $request->jumlah;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = PembelianDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon, $total)
    {
        $bayar = $total - ($diskon / 100 * $total);
        $data  = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
        ];

        return response()->json($data);
    }
}
