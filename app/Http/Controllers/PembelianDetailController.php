<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\RekamanStok;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianDetailController extends Controller
{
    public function index()
    {
        $id_pembelian = session('id_pembelian');
        $produk = Product::with('productVariants')
                    ->orderBy('name')
                    ->where(function($query) {
                        $query->where('type', 'simple')
                              ->orWhere('type', 'configurable');
                    })
                    ->get();
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
        $detail = PembelianDetail::with(['products', 'variant'])
            ->where('id_pembelian', $id)
            ->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($item) {
                return '<span class="label label-success">'. $item->products['id'] .'</span>';
            })
            ->addColumn('nama_produk', function ($item) {
                $name = $item->products['name'];
                if ($item->variant) {
                    $variantAttr = $item->variant->variantAttributes->pluck('attribute_value')->implode(', ');
                    $name .= ' (' . $variantAttr . ')';
                }
                return $name;
            })
            ->addColumn('harga_jual', function ($item) {
                $price = $item->variant ? $item->variant->price : $item->products['price'];
                return '<input type="number" class="form-control input-sm price" data-id="'. $item->products['id'] .'" value="'. $price .'">';
            })
            ->addColumn('harga_beli', function ($item) {
                return '<input type="number" class="form-control input-sm harga_beli" data-id="'. $item->products['id'] .'" data-uid="'. $item->id .'" value="'. $item->harga_beli .'">';
            })
            ->addColumn('jumlah', function ($item) {
                return '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id .'" value="'. $item->jumlah .'">';
            })
            ->addColumn('subtotal', function ($item) {
                return 'Rp. '. format_uang($item->subtotal);
            })
            ->addColumn('aksi', function ($item) {
                return '<div class="btn-group">
                            <button onclick="deleteData(`'. route('admin.pembelian_detail.destroy', $item->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                        </div>';
            })
            ->with([
                'total' => $detail->sum('subtotal'),
                'total_item' => $detail->sum('jumlah')
            ])
            ->rawColumns(['aksi', 'kode_produk', 'nama_produk', 'jumlah', 'harga_beli', 'harga_jual'])
            ->make(true);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $produk = Product::where('id', $request->id_produk)->first();
            if (! $produk) {
                return response()->json('Produk tidak ditemukan', 400);
            }

            $existingDetail = PembelianDetail::where('id_pembelian', $request->id_pembelian)
                                            ->where('id_produk', $request->id_produk)
                                            ->where('variant_id', $request->variant_id)
                                            ->first();

            if ($existingDetail) {
                return response()->json('Produk sudah ada dalam pembelian ini', 400);
            }

            $detail = new PembelianDetail();
            $detail->id_pembelian = $request->id_pembelian;
            $detail->id_produk = $produk->id;
            $detail->variant_id = $request->variant_id;
            
            if ($request->variant_id) {
                $variant = ProductVariant::find($request->variant_id);
                $detail->harga_beli = $variant ? $variant->harga_beli : ($produk->harga_beli ?? 0);
            } else {
                $detail->harga_beli = $produk->harga_beli ?? 0;
            }

            $detail->jumlah = 1;
            $detail->subtotal = $detail->harga_beli * $detail->jumlah;
            $detail->save();

            DB::commit();
            return response()->json('Data berhasil disimpan', 200);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json('Error: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $detail = PembelianDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_beli * $request->jumlah;
        $detail->update();
        
        // Note: Stock updates are now handled only when purchase is confirmed
        // This prevents double stock updates that were causing incorrect quantities
    }

    public function updateEdit(Request $request, $id)
    {
        $detail = PembelianDetail::where('id', $id)->first();
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_beli * $request->jumlah;
        $detail->update();
        
        // Note: Stock updates are now handled only when purchase is confirmed
        // This prevents double stock updates and inconsistencies
    }

    public function destroy($id)
    {
        $detail = PembelianDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function getVariants($productId)
    {
        $product = Product::with(['productVariants.variantAttributes'])->find($productId);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $variants = $product->productVariants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'price' => $variant->price,
                'harga_beli' => $variant->harga_beli ?? 0,
                'stock' => $variant->stock,
                'attributes' => $variant->variantAttributes->pluck('attribute_value')->implode(', '),
                'full_name' => $variant->variantAttributes->pluck('attribute_value')->implode(', ')
            ];
        });

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => $product->type
            ],
            'variants' => $variants
        ]);
    }

    public function getRealtimeStock($pembelianId)
    {
        $currentPurchaseItems = PembelianDetail::where('id_pembelian', $pembelianId)->get();
        
        $stockData = [];
        
        $products = Product::with(['productInventory', 'productVariants'])->get();
        
        foreach ($products as $product) {
            $purchasedQty = $currentPurchaseItems->where('id_produk', $product->id)->where('variant_id', null)->sum('jumlah');
            
            if ($product->type == 'simple') {
                $originalStock = $product->productInventory ? $product->productInventory->qty : 0;
                $projectedStock = $originalStock + $purchasedQty; // ADD purchased quantity to original stock
                
                $stockData[$product->id] = [
                    'type' => 'simple',
                    'original_stock' => $originalStock,
                    'purchased_qty' => $purchasedQty,
                    'projected_stock' => $projectedStock,
                    // Keep compatibility with frontend
                    'reserved_qty' => $purchasedQty,
                    'available_stock' => $projectedStock
                ];
            } else {
                $stockData[$product->id] = [
                    'type' => 'configurable',
                    'variants' => []
                ];
                
                foreach ($product->productVariants as $variant) {
                    $purchasedVariantQty = $currentPurchaseItems->where('id_produk', $product->id)->where('variant_id', $variant->id)->sum('jumlah');
                    $originalVariantStock = $variant->stock;
                    $projectedVariantStock = $originalVariantStock + $purchasedVariantQty; // ADD purchased quantity
                    
                    $stockData[$product->id]['variants'][$variant->id] = [
                        'original_stock' => $originalVariantStock,
                        'purchased_qty' => $purchasedVariantQty,
                        'projected_stock' => $projectedVariantStock,
                        // Keep compatibility with frontend
                        'reserved_qty' => $purchasedVariantQty,
                        'available_stock' => $projectedVariantStock
                    ];
                }
                
                $totalOriginalStock = $product->productVariants->sum('stock');
                $totalPurchasedQty = $currentPurchaseItems->where('id_produk', $product->id)->sum('jumlah');
                $totalProjectedStock = $totalOriginalStock + $totalPurchasedQty; // ADD purchased quantity
                
                $stockData[$product->id]['total_original_stock'] = $totalOriginalStock;
                $stockData[$product->id]['total_purchased_qty'] = $totalPurchasedQty;
                $stockData[$product->id]['total_projected_stock'] = $totalProjectedStock;
                // Keep compatibility with frontend
                $stockData[$product->id]['total_reserved_qty'] = $totalPurchasedQty;
                $stockData[$product->id]['total_available_stock'] = $totalProjectedStock;
            }
        }
        
        return response()->json($stockData);
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
