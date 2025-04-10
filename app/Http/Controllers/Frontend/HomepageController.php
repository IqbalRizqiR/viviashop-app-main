<?php

namespace App\Http\Controllers\Frontend;

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

class HomepageController extends Controller
{

    public function index()
    {
        // dd(public_path());
        $productActive = Product::where('type', 'simple')->get()->pluck('id');
        $productActives = array($productActive);
        $products = ProductCategory::with('categories', 'products')->limit(8)->whereIn('product_id', $productActives[0])->get();
        // dd($products);
        $categories = ProductCategory::with('products', 'categories')->whereIn('product_id', $productActives[0])->get();
        // $products = Product::all();
        $popular = Product::where('type', 'simple')->active()->limit(6)->get();
        $totalProduct = Product::where('type', 'simple')->count();
        // $productsImage = $products[4]->products->productImages;
        // dd($productsImage);
        // dd($products->categories);
        $slides = Slide::active()->orderBy('position', 'ASC')->get();
        // dd(Cart::content()->count());
        $cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
		view()->share('countCart', $cart);
        // $cart = Cart::content()->count();
        // view()->share('countCart', $cart);
        return view('frontend.homepage', compact('products', 'totalProduct', 'categories', 'popular', 'slides'));
    }

    public function detail($id)
    {
        $product = ProductCategory::where('product_id', $id)->with('categories', 'products')->first();
        $products = ProductCategory::with('categories', 'products')->get();
        $cart = Cart::content()->count();
        $setting = Setting::first();
view()->share('setting', $setting);
		view()->share('countCart', $cart);
        // dd($product->products->productImages);
        return view('frontend.shop.detail', compact('product', 'products'));
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
            // dd($tanggal);

            $total_penjualan = Order::where('payment_status', 'paid')->where('order_date', 'LIKE', "$tanggal%")->orWhere('created_at', 'LIKE', "%$tanggal")->sum('grand_total');
            $order = Order::where('payment_status', 'paid')->where('order_date', 'LIKE', "$tanggal%")->orWhere('created_at', 'LIKE', "%$tanggal")->get();
            $total_pembelian = Pembelian::where('waktu', 'LIKE', "%$tanggal%")->orWhere('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal%")->sum('nominal');
            $total_shipping = Order::where('payment_status', 'paid')->where('order_date', 'LIKE', "$tanggal%")->sum('shipping_cost');
            $total_base_price = 0;

            if ($order->count() > 1) {
                foreach ($order as $orders) {
                    $order_items = OrderItem::where('order_id', $orders->id)->get();

                    foreach($order_items as $orderr) {
                        $base_price = $orderr->product->harga_beli * $orderr->qty;
                        $total_base_price += $base_price;
                    }
                }
            } elseif($order->count() < 1) {
                $total_base_price = 0;
            } else {
                $order_items = OrderItem::where('order_id', $order->id)->get();

                foreach($order_items as $order) {
                    $base_price = $order->product->price * $order->qty;
                    $total_base_price += ($order->base_total - $base_price);
                }
            }

            // dd($total_shipping);

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
        // dd(public_path());
        $data = $this->getReportsData($awal, $akhir);
        $pdf  = Pdf::loadView('admin.reports.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }

    public function shop(Request $request)
    {
        $produk = Product::where('type', 'simple')->get()->pluck('id');
        $produkss = array($produk);
        // dd($produkss);
        $products = ProductCategory::with(['products', 'categories'])->whereIn('product_id', $produkss[0])->get();
        // dd($products);
        $producteds = ProductCategory::with(['products', 'categories'])->whereIn('product_id', $produkss[0])->get();
        $cart = Cart::content()->count();
        $setting = Setting::first();
view()->share('setting', $setting);
		view()->share('countCart', $cart);
        $categories = Category::all();

        if (count($products) <= 1) {
            if (request()->has('search')) {
                // dd($products[0]->products);
                $products = $products[0]->products->where('name', 'like', '%' . request()->get('search', ''). '%')->where('type', 'simple')->first();
                // dd($products);
                $product = Product::where('id', $products->id)->first();
                $products->productImages = $product->productImages;
                // dd(count($products->categories));
            }
        } else {
            if (request()->has('search')) {
                // dd($products[0]->products);
                foreach ($products as $row) {
                    $products = $row->products->where('name', 'like', '%' . request()->get('search', ''). '%')->where('type', 'simple')->get();
                    $product = Product::where('id', $row->products->id)->first();
                    $products->productImages = $product->productImages;
                    // dd($products[0]);
                }
            }
        }

        if (request()->has('search')) {
            $producted = $products;
        } else {
            $producted = $producteds;
        }
            // dd($producted[0]->productInventory);


        return view('frontend.shop.index', [
            'products' => $producted,
            'categories' => $categories,
        ]);
    }
    public function shopCetak(Request $request)
    {
        $cat = Category::where('slug', 'like', '%' . 'cetak' . '%')->get()->pluck('id');
        $cats = array($cat);
        // dd($cats[0][0]);
        $products = ProductCategory::with(['products', 'categories'])->whereIn('category_id', $cats[0])->get();
        // dd($products);
        $producteds = ProductCategory::with(['products', 'categories'])->whereIn('category_id', $cats[0])->get();
        $cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
		view()->share('countCart', $cart);
        $categories = Category::all();

        if (count($products) <= 1) {
            if (request()->has('search')) {
                // dd($products[0]->products);
                $products = $products[0]->products->where('name', 'like', '%' . request()->get('search', ''). '%')->first();
                // dd($products);
                $product = Product::where('id', $products->id)->first();
                $products->productImages = $product->productImages;
                // dd(count($products->categories));
            }
        } else {
            if (request()->has('search')) {
                // dd($products[0]->products);
                foreach ($products as $row) {
                    $products = $row->products->where('name', 'like', '%' . request()->get('search', ''). '%')->get();
                    $product = Product::where('id', $row->products->id)->first();
                    $products->productImages = $product->productImages;
                }
            }
        }

        if (request()->has('search')) {
            $producted = $products;
        } else {
            $producted = $producteds;
        }


        return view('frontend.shop.index', [
            'products' => $producted,
            'categories' => $categories,
        ]);
    }
}
