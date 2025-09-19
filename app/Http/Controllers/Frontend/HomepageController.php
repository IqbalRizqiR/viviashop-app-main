<?php

namespace App\Http\Controllers\Frontend;

use App\Exports\ReportRevenue;
use App\Models\Slide;
use App\Models\Product;
use Exception;
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
        
        $parentProduct = Product::with(['productInventory', 'productVariants.variantAttributes'])->find($id);
        
        if (!$parentProduct) {
            abort(404);
        }
        
        $productCategory = ProductCategory::where('product_id', $parentProduct->id)->with('categories')->first();
        
        $configurable_attributes = collect();
        $variants = collect();
        $variantOptions = [];
        
        // Handle product types properly
        $hasVariants = $parentProduct->activeVariants()->count() > 0;
        
        // For simple products with variants (data inconsistency), 
        // we should either clean up the variants or treat as configurable
        // Here we'll ignore the variants for simple products to maintain consistency
        if ($parentProduct->type == 'configurable' && $hasVariants) {
            $configurable_attributes = \App\Models\Attribute::where('is_configurable', true)
                ->with(['attribute_variants.attribute_options'])
                ->get();
            $variants = $parentProduct->activeVariants()->with(['variantAttributes'])->get();
            
            if ($variants->count() > 0) {
                try {
                    $variantOptions = $parentProduct->getVariantOptions();
                    
                    $minPrice = $variants->min('price');
                    $maxPrice = $variants->max('price');
                    $priceRange = [
                        'min' => $minPrice,
                        'max' => $maxPrice,
                        'same' => $minPrice == $maxPrice
                    ];
                } catch (Exception $e) {
                    $variantOptions = [];
                    $priceRange = null;
                }
            } else {
                $priceRange = null;
            }
        } else {
            $priceRange = null;
        }
        
        $cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
        view()->share('countCart', $cart);
        return view('frontend.shop.detail', compact('parentProduct', 'productCategory', 'configurable_attributes', 'variants', 'variantOptions', 'priceRange'));
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

        $currentDate = $awal;
        while (strtotime($currentDate) <= strtotime($akhir)) {
            $tanggal = $currentDate;
            $currentDate = date('Y-m-d', strtotime("+1 day", strtotime($currentDate)));

            $orders = Order::where('payment_status', 'paid')
                ->where('grand_total', '>', 0)
                ->whereDate('created_at', $tanggal)
                ->with('orderItems.product')
                ->get();

            $validOrders = $orders->filter(function($order) {
                if (!$order->orderItems || count($order->orderItems) == 0) {
                    return false;
                }
                
                $order_net_sales = $order->grand_total - $order->shipping_cost;
                if ($order_net_sales <= 0) {
                    return false;
                }
                
                $order_total_cost = 0;
                foreach ($order->orderItems as $orderItem) {
                    if ($orderItem->product && $orderItem->product->harga_beli) {
                        $order_total_cost += ($orderItem->product->harga_beli * $orderItem->qty);
                    }
                }
                
                return $order_net_sales >= ($order_total_cost * 0.1);
            });
            
            $total_penjualan = $validOrders->sum('grand_total');

            $total_pembelian = Pembelian::whereDate('created_at', $tanggal)
                ->sum('total_harga');

            $total_pengeluaran = Pengeluaran::whereDate('created_at', $tanggal)
                ->sum('nominal');

            $total_shipping = $validOrders->sum('shipping_cost');

            $total_cost_of_goods = 0;
            $total_profit_margin = 0;

            foreach ($validOrders as $order) {
                if ($order->orderItems && count($order->orderItems) > 0) {
                    $order_total_base_price = $order->orderItems->sum(function($item) {
                        return $item->base_price * $item->qty;
                    });
                    
                    $order_net_sales = $order->grand_total - $order->shipping_cost;
                    
                    if ($order_total_base_price <= 0 || $order_net_sales <= 0) {
                        continue;
                    }
                    
                    $order_total_cost = 0;
                    foreach ($order->orderItems as $orderItem) {
                        if ($orderItem->product && $orderItem->product->harga_beli) {
                            $order_total_cost += ($orderItem->product->harga_beli * $orderItem->qty);
                        }
                    }
                    
                    if ($order_net_sales < ($order_total_cost * 0.1)) {
                        continue;
                    }
                    
                    foreach ($order->orderItems as $orderItem) {
                        if ($orderItem->product && $orderItem->product->harga_beli) {
                            $cost_price = $orderItem->product->harga_beli;
                            $base_price = $orderItem->base_price;
                            $qty = $orderItem->qty;
                            
                            $item_proportion = ($base_price * $qty) / $order_total_base_price;
                            $actual_selling_price = ($order_net_sales * $item_proportion) / $qty;
                            
                            $total_cost_of_goods += ($cost_price * $qty);
                            $profit_per_item = $actual_selling_price - $cost_price;
                            $total_profit_margin += ($profit_per_item * $qty);
                        }
                    }
                }
            }

            $net_sales = $total_penjualan - $total_shipping;
            $keuntungan = $total_profit_margin - $total_pengeluaran;

            $total_keuntungan += $keuntungan;
            $pendapatan = $total_penjualan;
            $total_pendapatan += $pendapatan;
            $total_pembelian_seluruh += $total_pembelian;
            $total_penjualan_seluruh += $total_penjualan;
            $total_pengeluaran_seluruh += $total_pengeluaran;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = $tanggal;
            $row['penjualan'] = $total_penjualan;
            $row['pembelian'] = $total_pembelian;
            $row['pengeluaran'] = $total_pengeluaran;
            $row['pendapatan'] = $pendapatan;
            $row['keuntungan'] = $keuntungan;
            $row['cost_of_goods'] = $total_cost_of_goods;
            $row['net_sales'] = $net_sales;

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penjualan' => $total_penjualan_seluruh,
            'pembelian' => $total_pembelian_seluruh,
            'pengeluaran' => $total_pengeluaran_seluruh,
            'pendapatan' => $total_pendapatan,
            'keuntungan' => $total_keuntungan,
            'cost_of_goods' => 0,
            'net_sales' => 0,
        ];

        return $data;
    }

    public function data($awal, $akhir)
    {
        $rawData = $this->getReportsData($awal, $akhir);
        
        $formattedData = collect($rawData)->map(function($item) {
            if (empty($item['tanggal'])) {
                return [
                    'DT_RowIndex' => '<strong>TOTAL</strong>',
                    'tanggal' => '<strong>TOTAL</strong>',
                    'penjualan' => '<strong>' . format_uang($item['penjualan']) . '</strong>',
                    'pembelian' => '<strong>' . format_uang($item['pembelian']) . '</strong>',
                    'pengeluaran' => '<strong>' . format_uang($item['pengeluaran']) . '</strong>',
                    'pendapatan' => '<strong>' . format_uang($item['pendapatan']) . '</strong>',
                    'keuntungan' => '<strong>' . format_uang($item['keuntungan']) . '</strong>',
                ];
            }
            
            $profitMargin = $item['penjualan'] > 0 ? (($item['keuntungan'] / $item['penjualan']) * 100) : 0;
            $profitStatus = $item['keuntungan'] > 0 ? 'profit' : ($item['keuntungan'] < 0 ? 'loss' : 'break-even');
            
            return [
                'DT_RowIndex' => $item['DT_RowIndex'],
                'tanggal' => tanggal_indonesia($item['tanggal'], false),
                'penjualan' => format_uang($item['penjualan']),
                'pembelian' => format_uang($item['pembelian']),
                'pengeluaran' => format_uang($item['pengeluaran']),
                'pendapatan' => format_uang($item['pendapatan']),
                'keuntungan' => '<span class="badge badge-' . 
                               ($profitStatus == 'profit' ? 'success' : 
                                ($profitStatus == 'loss' ? 'danger' : 'warning')) . '">' . 
                               format_uang($item['keuntungan']) . 
                               ' (' . number_format($profitMargin, 1) . '%)</span>',
                'detail' => [
                    'gross_sales' => format_uang($item['penjualan']),
                    'shipping_cost' => format_uang($item['penjualan'] - $item['net_sales']),
                    'net_sales' => format_uang($item['net_sales']),
                    'cost_of_goods' => format_uang($item['cost_of_goods']),
                    'expenses' => format_uang($item['pengeluaran']),
                    'profit_margin' => format_uang($item['keuntungan']),
                    'profit_percentage' => number_format($profitMargin, 2) . '%',
                    'breakdown' => "Penjualan Kotor: " . format_uang($item['penjualan']) . 
                                 " - Ongkir: " . format_uang($item['penjualan'] - $item['net_sales']) .
                                 " = Penjualan Bersih: " . format_uang($item['net_sales']) .
                                 " - HPP: " . format_uang($item['cost_of_goods']) .
                                 " - Pengeluaran: " . format_uang($item['pengeluaran']) .
                                 " = Keuntungan: " . format_uang($item['keuntungan'])
                ]
            ];
        });

        return datatables()
            ->of($formattedData)
            ->rawColumns(['DT_RowIndex', 'tanggal', 'penjualan', 'pembelian', 'pengeluaran', 'pendapatan', 'keuntungan'])
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

        // Handle search with fuzzy matching
        if ($request->has('search')) {
            $searchTerm = $request->get('search', '');
            $filteredProducts = collect();
            
            foreach ($products as $row) {
                if ($row->products) {
                    // Exact match first
                    if (stripos($row->products->name, $searchTerm) !== false) {
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
                    } else {
                        // Fuzzy matching for typos
                        $similarity = $this->calculateSimilarity($searchTerm, $row->products->name);
                        if ($similarity >= 50) { // 50% similarity threshold
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
                }
            }
            
            $producted = $filteredProducts;
        } else {
            $producted = $producteds;
        }

        // Handle sorting
        if ($request->has('sort')) {
            $sortBy = $request->get('sort');
            
            switch ($sortBy) {
                case 'name_asc':
                    $producted = $producted->sortBy(function($item) {
                        return $item->products->name ?? '';
                    });
                    break;
                case 'name_desc':
                    $producted = $producted->sortByDesc(function($item) {
                        return $item->products->name ?? '';
                    });
                    break;
                case 'price_asc':
                    $producted = $producted->sortBy(function($item) {
                        return $item->products->price ?? 0;
                    });
                    break;
                case 'price_desc':
                    $producted = $producted->sortByDesc(function($item) {
                        return $item->products->price ?? 0;
                    });
                    break;
                case 'newest':
                    $producted = $producted->sortByDesc(function($item) {
                        return $item->products->created_at ?? '';
                    });
                    break;
            }
        }

        return view('frontend.shop.index', [
            'products' => $producted,
            'categories' => $categories,
        ]);
    }

    /**
     * Calculate similarity percentage between two strings
     * Supports fuzzy matching for typos
     */
    private function calculateSimilarity($search, $target)
    {
        if (empty($search) || empty($target)) {
            return 0;
        }
        
        $search = strtolower(trim($search));
        $target = strtolower(trim($target));
        
        // If search term is contained in target, return high similarity
        if (strpos($target, $search) !== false) {
            return 90;
        }
        
        // If target is contained in search, also return high similarity
        if (strpos($search, $target) !== false) {
            return 85;
        }
        
        try {
            // Calculate similarity using multiple methods
            $levSimilarity = $this->levenshteinSimilarity($search, $target);
            $jaSimilarity = $this->jaroWinklerSimilarity($search, $target);
            $substrSimilarity = $this->substringMatchingSimilarity($search, $target);
            
            // Return the highest similarity score
            return max($levSimilarity, $jaSimilarity, $substrSimilarity);
        } catch (Exception $e) {
            // Fallback to simple string comparison if algorithms fail
            return $this->simpleSimilarity($search, $target);
        }
    }
    
    /**
     * Simple similarity fallback method
     */
    private function simpleSimilarity($search, $target)
    {
        $searchLen = strlen($search);
        $targetLen = strlen($target);
        
        if ($searchLen == 0 || $targetLen == 0) return 0;
        
        $common = 0;
        $searchChars = str_split($search);
        $targetChars = str_split($target);
        
        foreach ($searchChars as $char) {
            if (in_array($char, $targetChars)) {
                $common++;
            }
        }
        
        return ($common / $searchLen) * 100;
    }
    
    /**
     * Levenshtein distance similarity
     */
    private function levenshteinSimilarity($s1, $s2)
    {
        $maxLen = max(strlen($s1), strlen($s2));
        if ($maxLen == 0) return 100;
        
        $distance = levenshtein($s1, $s2);
        return (1 - $distance / $maxLen) * 100;
    }
    
    /**
     * Jaro-Winkler similarity
     */
    private function jaroWinklerSimilarity($s1, $s2)
    {
        $len1 = strlen($s1);
        $len2 = strlen($s2);
        
        if ($len1 == 0 && $len2 == 0) return 100;
        if ($len1 == 0 || $len2 == 0) return 0;
        
        $matchWindow = intval(max($len1, $len2) / 2) - 1;
        if ($matchWindow < 0) $matchWindow = 0;
        
        $s1Matches = array_fill(0, $len1, false);
        $s2Matches = array_fill(0, $len2, false);
        
        $matches = 0;
        $transpositions = 0;
        
        // Convert strings to arrays for safer access
        $s1Array = str_split($s1);
        $s2Array = str_split($s2);
        
        // Find matches
        for ($i = 0; $i < $len1; $i++) {
            $start = max(0, $i - $matchWindow);
            $end = min($i + $matchWindow + 1, $len2);
            
            for ($j = $start; $j < $end; $j++) {
                if ($s2Matches[$j] || $s1Array[$i] != $s2Array[$j]) continue;
                
                $s1Matches[$i] = true;
                $s2Matches[$j] = true;
                $matches++;
                break;
            }
        }
        
        if ($matches == 0) return 0;
        
        // Find transpositions
        $k = 0;
        for ($i = 0; $i < $len1; $i++) {
            if (!$s1Matches[$i]) continue;
            
            while (!$s2Matches[$k]) $k++;
            
            if ($s1Array[$i] != $s2Array[$k]) $transpositions++;
            $k++;
        }
        
        $jaro = ($matches / $len1 + $matches / $len2 + ($matches - $transpositions / 2) / $matches) / 3;
        
        // Jaro-Winkler prefix scaling
        $prefix = 0;
        $minLen = min($len1, $len2);
        for ($i = 0; $i < $minLen && $i < 4; $i++) {
            if ($s1Array[$i] == $s2Array[$i]) {
                $prefix++;
            } else {
                break;
            }
        }
        
        return ($jaro + 0.1 * $prefix * (1 - $jaro)) * 100;
    }
    
    /**
     * Substring matching similarity
     */
    private function substringMatchingSimilarity($search, $target)
    {
        $searchLen = strlen($search);
        $targetLen = strlen($target);
        
        if ($searchLen == 0 || $targetLen == 0) return 0;
        
        $commonSubstrings = 0;
        $searchWords = explode(' ', $search);
        
        foreach ($searchWords as $word) {
            if (strlen($word) >= 2 && strpos($target, $word) !== false) {
                $commonSubstrings += strlen($word);
            }
        }
        
        return ($commonSubstrings / $searchLen) * 100;
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
        $cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
        view()->share('countCart', $cart);
        $categories = Category::all();
        
        $producted = $products;
        
        // Handle search with fuzzy matching
        if ($request->has('search')) {
            $searchTerm = $request->get('search', '');
            $filteredProducts = collect();
            
            foreach ($products as $row) {
                if ($row->products) {
                    // Exact match first
                    if (stripos($row->products->name, $searchTerm) !== false) {
                        $filteredProducts->push($row);
                    } else {
                        // Fuzzy matching for typos
                        $similarity = $this->calculateSimilarity($searchTerm, $row->products->name);
                        if ($similarity >= 50) { // 50% similarity threshold
                            $filteredProducts->push($row);
                        }
                    }
                }
            }
            
            $producted = $filteredProducts;
        }

        // Handle sorting
        if ($request->has('sort')) {
            $sortBy = $request->get('sort');
            
            switch ($sortBy) {
                case 'name_asc':
                    $producted = $producted->sortBy(function($item) {
                        return $item->products->name ?? '';
                    });
                    break;
                case 'name_desc':
                    $producted = $producted->sortByDesc(function($item) {
                        return $item->products->name ?? '';
                    });
                    break;
                case 'price_asc':
                    $producted = $producted->sortBy(function($item) {
                        return $item->products->price ?? 0;
                    });
                    break;
                case 'price_desc':
                    $producted = $producted->sortByDesc(function($item) {
                        return $item->products->price ?? 0;
                    });
                    break;
                case 'newest':
                    $producted = $producted->sortByDesc(function($item) {
                        return $item->products->created_at ?? '';
                    });
                    break;
            }
        }
        
        return view('frontend.shop.index', [
            'products' => $producted,
            'categories' => $categories,
        ]);
    }
}
