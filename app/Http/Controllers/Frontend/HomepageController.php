<?php

namespace App\Http\Controllers\Frontend;

use App\Exports\ReportRevenue;
use App\Models\Slide;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\ProductCategory;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Http\Controllers\InstagramController; // Import the InstagramController
use Maatwebsite\Excel\Facades\Excel;

class HomepageController extends Controller
{
    public function index()
    {
        $productActive = Product::where(function($query) {
            $query->where('type', 'simple')
                  ->whereNull('parent_id')
                  ->orWhere('type', 'configurable');
        })->get()->pluck('id');
        $productActives = array($productActive);
        $products = ProductCategory::with('categories', 'products')->limit(8)->whereIn('product_id', $productActives[0])->get();
        $categories = ProductCategory::with('products', 'categories')->whereIn('product_id', $productActives[0])->get();
        $categoriesCount = ProductCategory::with('products', 'categories')->whereIn('product_id', $productActives[0])->pluck('category_id');
        $categoriesName = Category::whereIn('id', $categoriesCount)->get();
        $popular = Product::where(function($query) {
            $query->where('type', 'simple')
                  ->whereNull('parent_id')
                  ->orWhere('type', 'configurable');
        })->active()->limit(6)->get();
        $totalProduct = Product::where(function($query) {
            $query->where('type', 'simple')
                  ->whereNull('parent_id')
                  ->orWhere('type', 'configurable');
        })->count();
        $totalOrder = Order::where('payment_status', 'paid')->count();
        $slides = Slide::active()->orderBy('position', 'ASC')->get();
        $cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
        view()->share('countCart', $cart);
        return view('frontend.homepage', compact('products', 'totalOrder', 'totalProduct', 'categories', 'categoriesName', 'popular', 'slides'));
    }

    public function detail($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            abort(404);
        }
        
        if ($product->parent_id) {
            return redirect()->route('shop-detail', $product->parent_id);
        }
        
        $parentProduct = Product::with(['productInventory', 'variants.productInventory'])->find($id);
        
        if (!$parentProduct) {
            abort(404);
        }
        
        $productCategory = ProductCategory::where('product_id', $parentProduct->id)->with('categories')->first();
        
        $configurable_attributes = collect();
        if ($parentProduct->type == 'configurable') {
            $configurable_attributes = \App\Models\Attribute::where('is_configurable', true)
                ->with(['attribute_variants.attribute_options'])
                ->get();
        }
        
        $variants = $parentProduct->variants()->with(['productInventory'])->get();
        
        foreach ($variants as $variant) {
            $variant->product_attribute_values = \App\Models\ProductAttributeValue::where('product_id', $variant->id)
                ->with(['attribute', 'attribute_variant', 'attribute_option'])
                ->get();
        }
        
        $cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
        view()->share('countCart', $cart);
        return view('frontend.shop.detail', compact('parentProduct', 'productCategory', 'configurable_attributes', 'variants'));
    }

    public function reports(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('admin.reports.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function exportExcel($awal, $akhir)
    {
        $fileName = "Laporan-Revenue-{$awal}_{$akhir}.xlsx";

        return Excel::download(
            new ReportRevenue($awal, $akhir),
            $fileName
        );
    }

    public function getReportsData($awal, $akhir)
    {
        $no = 1;
        $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;
        $total_keuntungan = 0;
        $total_shipping = 0;
        $total_pembelian_seluruh = 0;
        $total_penjualan_seluruh = 0;
        $total_pengeluaran_seluruh = 0;

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $total_penjualan = Order::where('payment_status', 'paid')->where('order_date', 'LIKE', "$tanggal%")->orWhere('created_at', 'LIKE', "%$tanggal")->sum('grand_total');
            $order = Order::where('payment_status', 'paid')->where('order_date', 'LIKE', "$tanggal%")->orWhere('created_at', 'LIKE', "%$tanggal")->get();
            $total_pembelian = Pembelian::where('waktu', 'LIKE', "%$tanggal%")->orWhere('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal%")->sum('nominal');
            $total_shipping = Order::where('payment_status', 'paid')->where('order_date', 'LIKE', "$tanggal%")->sum('shipping_cost');
            $total_base_price = 0;
            // dd($order->count());

            if ($order->count() > 1) {
                foreach ($order as $orders) {
                    $order_items = OrderItem::where('order_id', $orders->id)->get();

                    foreach($order_items as $orderr) {
                        $base_price = $orderr->product->harga_beli * $orderr->qty;
                        $total_base_price += $base_price;
                    }
                }
            } elseif($order->count() < 1 || $order->count() == 0) {
                $total_base_price = 0;
            } else {
                // dd($order[0]->id);
                $order_items = OrderItem::where('order_id', $order[0]->id)->get();

                foreach($order_items as $order) {
                    $base_price = $order->product->price * $order->qty;
                    $total_base_price += ($order->base_total - $base_price);
                }
            }

            $keuntungan = ($total_penjualan - $total_shipping) - $total_base_price;
            $total_keuntungan += $keuntungan;

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
            $row['keuntungan'] = format_uang($keuntungan);
            $row['tanggal'] = $tanggal;
            $row['total_base_price'] = $total_base_price;

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penjualan' => format_uang($total_penjualan_seluruh),
            'pembelian' => format_uang($total_pembelian_seluruh),
            'pengeluaran' => format_uang($total_pengeluaran_seluruh),
            'pendapatan' => format_uang($total_pendapatan),
            'keuntungan' => format_uang($total_keuntungan),
        ];

        return $data;
    }

    public function data($awal, $akhir)
    {
        $data = $this->getReportsData($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getReportsData($awal, $akhir);
        $pdf  = Pdf::loadView('admin.reports.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }

    public function shop(Request $request)
    {
        $produk = Product::where(function($query) {
            $query->where('type', 'simple')
                  ->whereNull('parent_id')
                  ->orWhere('type', 'configurable');
        })->get()->pluck('id');
        $produkss = array($produk);
        $products = ProductCategory::with(['products', 'categories'])->whereIn('product_id', $produkss[0])->get();
        $producteds = ProductCategory::with(['products', 'categories'])->whereIn('product_id', $produkss[0])->get();
        $cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
        view()->share('countCart', $cart);
        $categories = Category::all();

        if ($request->has('search')) {
            $searchTerm = $request->get('search', '');
            $filteredProducts = collect();
            
            foreach ($products as $row) {
                if ($row->products && stripos($row->products->name, $searchTerm) !== false) {
                    if ($row->products->parent_id) {
                        $parentProduct = Product::find($row->products->parent_id);
                        if ($parentProduct) {
                            $existingProduct = $filteredProducts->first(function($item) use ($parentProduct) {
                                return $item->products && $item->products->id === $parentProduct->id;
                            });
                            
                            if (!$existingProduct) {
                                $parentProductCategory = ProductCategory::where('product_id', $parentProduct->id)->first();
                                if ($parentProductCategory) {
                                    $filteredProducts->push($parentProductCategory);
                                }
                            }
                        }
                    } else {
                        $filteredProducts->push($row);
                    }
                }
            }
            
            $producted = $filteredProducts;
        } else {
            $producted = $producteds;
        }

        return view('frontend.shop.index', [
            'products' => $producted,
            'categories' => $categories,
        ]);
    }

    public function shopCetak(Request $request)
    {
        $cat = Category::where('slug', 'like', '%' . 'cetak' . '%')->get()->pluck('id');
        $cats = array($cat);
        $products = ProductCategory::with(['products', 'categories'])->whereIn('category_id', $cats[0])->get();
        $producteds = ProductCategory::with(['products', 'categories'])->whereIn('category_id', $cats[0])->get();
        $cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
        view()->share('countCart', $cart);
        $categories = Category::all();
        return view('frontend.shop.index', compact('products', 'categories', 'producteds'));
    }
    public function shopCategory(Request $request, $slug)
    {
        $cat = Category::where('slug', 'like', '%' . $slug . '%')->get()->pluck('id');
        $cats = array($cat);
        $products = ProductCategory::with(['products', 'categories'])->whereIn('category_id', $cats[0])->get();
        $producteds = ProductCategory::with(['products', 'categories'])->whereIn('category_id', $cats[0])->get();
        $cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
        view()->share('countCart', $cart);
        $categories = Category::all();
        return view('frontend.shop.index', compact('products', 'categories', 'producteds'));
    }
}
