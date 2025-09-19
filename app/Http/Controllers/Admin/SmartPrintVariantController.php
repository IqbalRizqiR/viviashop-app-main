<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SmartPrintVariantService;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class SmartPrintVariantController extends Controller
{
    protected $smartPrintVariantService;

    public function __construct()
    {
        $this->smartPrintVariantService = new SmartPrintVariantService();
    }

    /**
     * Show the smart print variant management page
     */
    public function index()
    {
        // Get print service products with missing print fields
        // Using same filter as StockManagementService
        $problematicVariants = ProductVariant::where('is_active', true)
            ->whereHas('product', function($query) {
                $query->where('is_print_service', true)
                      ->where('status', 1);
            })
            ->where(function($query) {
                $query->whereNull('paper_size')->orWhereNull('print_type');
            })
            ->with('product')
            ->get();

        // Get print service products without variants
        // Using same filter as StockManagementService
        $productsWithoutVariants = Product::where('is_print_service', true)
            ->where('status', 1)
            ->whereDoesntHave('productVariants')  // Use productVariants instead of variants
            ->get();

        return view('admin.smart-print-variant.index', compact('problematicVariants', 'productsWithoutVariants'));
    }

    /**
     * Auto-fix existing variants
     */
    public function autoFix()
    {
        $results = $this->smartPrintVariantService->autoFixPrintServiceVariants();
        
        return response()->json([
            'success' => true,
            'message' => "Auto-fix completed! Fixed {$results['fixed']} variants, skipped {$results['skipped']} variants.",
            'details' => $results['details']
        ]);
    }

    /**
     * Create smart print variants for a product
     */
    public function createVariants(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Check if product already has variants
        if($product->productVariants()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Product already has variants. Use auto-fix instead.'
            ]);
        }

        $basePrice = $request->input('base_price', 2000);
        $variants = $this->smartPrintVariantService->createSmartPrintVariants($product, $basePrice);
        
        return response()->json([
            'success' => true,
            'message' => "Created " . count($variants) . " variants for {$product->name}",
            'variants' => collect($variants)->map(function($variant) {
                return [
                    'name' => $variant->name,
                    'paper_size' => $variant->paper_size,
                    'print_type' => $variant->print_type,
                    'price' => $variant->price,
                    'stock' => $variant->stock
                ];
            })
        ]);
    }
}