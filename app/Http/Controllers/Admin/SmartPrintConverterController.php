<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\ProductVariantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmartPrintConverterController extends Controller
{
    protected $productVariantService;

    public function __construct(ProductVariantService $productVariantService)
    {
        $this->productVariantService = $productVariantService;
    }

    public function index(Request $request)
    {
        $query = Product::withCount('productVariants');

        // Search functionality
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter functionality
        $filter = $request->get('filter', 'all');
        switch ($filter) {
            case 'not_smart_print':
                $query->where(function ($q) {
                    $q->where('is_print_service', false)
                      ->orWhere('is_smart_print_enabled', false)
                      ->orWhereNull('is_print_service')
                      ->orWhereNull('is_smart_print_enabled');
                });
                break;
            case 'already_smart_print':
                $query->where('is_print_service', true)
                      ->where('is_smart_print_enabled', true);
                break;
            case 'all':
            default:
                // No additional filter
                break;
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => Product::count(),
            'smart_print' => Product::where('is_print_service', true)
                                   ->where('is_smart_print_enabled', true)
                                   ->count(),
            'regular' => Product::where(function ($q) {
                $q->where('is_print_service', false)
                  ->orWhere('is_smart_print_enabled', false)
                  ->orWhereNull('is_print_service')
                  ->orWhereNull('is_smart_print_enabled');
            })->count(),
        ];

        return view('admin.smart-print-converter.index', compact('products', 'stats'));
    }

    public function convert(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            // Store original values for logging
            $originalPrintService = $product->is_print_service;
            $originalSmartPrint = $product->is_smart_print_enabled;

            // Convert to Smart Print Product
            $product->update([
                'is_print_service' => true,
                'is_smart_print_enabled' => true,
                'status' => 1, // Ensure product is active
            ]);

            // Check if BW and Color variants exist (including by SKU and similar names)
            $existingVariants = $product->productVariants()
                ->where(function($query) {
                    $query->whereIn('name', ['BW', 'Color'])
                          ->orWhere('name', 'like', '%Black & White%')
                          ->orWhere('name', 'like', '%Color%')
                          ->orWhere('name', 'like', '%BW%');
                })
                ->get();

            // Check what we actually have
            $hasBWVariant = $existingVariants->contains(function($variant) {
                return in_array($variant->name, ['BW']) || 
                       stripos($variant->name, 'Black') !== false || 
                       stripos($variant->name, 'BW') !== false;
            });

            $hasColorVariant = $existingVariants->contains(function($variant) {
                return in_array($variant->name, ['Color']) || 
                       stripos($variant->name, 'Color') !== false;
            });

            // Also check for SKU conflicts
            $expectedBWSku = $this->generateUniqueSku($product->sku . '-BW');
            $expectedColorSku = $this->generateUniqueSku($product->sku . '-Color');

            $variantsCreated = [];

            // Create BW variant if not exists
            if (!$hasBWVariant) {
                $this->productVariantService->createVariant($product, [
                    'name' => 'BW',
                    'sku' => $expectedBWSku,
                    'price' => $product->price ?? 0,
                    'stock' => 0,
                    'weight' => 0,
                ], []); // Empty attributes array
                $variantsCreated[] = 'BW';
            }

            // Create Color variant if not exists
            if (!$hasColorVariant) {
                $this->productVariantService->createVariant($product, [
                    'name' => 'Color',
                    'sku' => $expectedColorSku,
                    'price' => ($product->price ?? 0) * 1.5, // Color variant 1.5x price
                    'stock' => 0,
                    'weight' => 0,
                ], []); // Empty attributes array
                $variantsCreated[] = 'Color';
            }

            DB::commit();

            // Create success message
            $message = "✅ Produk '{$product->name}' berhasil dikonversi menjadi Smart Print Product!";
            
            if (!empty($variantsCreated)) {
                $message .= " Variants yang dibuat: " . implode(', ', $variantsCreated) . ".";
            } else {
                $message .= " Variants BW dan Color sudah ada.";
            }

            $message .= " Produk sekarang akan muncul di Stock Management Print Service.";

            return redirect()->route('admin.smart-print-converter.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.smart-print-converter.index')
                ->with('error', "❌ Gagal mengkonversi produk: " . $e->getMessage());
        }
    }

    public function bulkConvert(Request $request)
    {
        $productIds = $request->input('product_ids', []);
        
        if (empty($productIds)) {
            return redirect()->route('admin.smart-print-converter.index')
                ->with('error', 'Pilih minimal 1 produk untuk dikonversi.');
        }

        try {
            DB::beginTransaction();

            $successCount = 0;
            $errors = [];

            foreach ($productIds as $productId) {
                try {
                    $product = Product::findOrFail($productId);

                    // Convert to Smart Print Product
                    $product->update([
                        'is_print_service' => true,
                        'is_smart_print_enabled' => true,
                        'status' => 1,
                    ]);

                    // Check and create variants with smart detection
                    $existingVariants = $product->productVariants()
                        ->where(function($query) {
                            $query->whereIn('name', ['BW', 'Color'])
                                  ->orWhere('name', 'like', '%Black & White%')
                                  ->orWhere('name', 'like', '%Color%')
                                  ->orWhere('name', 'like', '%BW%');
                        })
                        ->get();

                    // Check what we actually have
                    $hasBWVariant = $existingVariants->contains(function($variant) {
                        return in_array($variant->name, ['BW']) || 
                               stripos($variant->name, 'Black') !== false || 
                               stripos($variant->name, 'BW') !== false;
                    });

                    $hasColorVariant = $existingVariants->contains(function($variant) {
                        return in_array($variant->name, ['Color']) || 
                               stripos($variant->name, 'Color') !== false;
                    });

                    // Create BW variant if not exists
                    if (!$hasBWVariant) {
                        $bwSku = $this->generateUniqueSku($product->sku . '-BW');
                        $this->productVariantService->createVariant($product, [
                            'name' => 'BW',
                            'sku' => $bwSku,
                            'price' => $product->price ?? 0,
                            'stock' => 0,
                            'weight' => 0,
                        ], []); // Empty attributes array
                    }

                    // Create Color variant if not exists
                    if (!$hasColorVariant) {
                        $colorSku = $this->generateUniqueSku($product->sku . '-Color');
                        $this->productVariantService->createVariant($product, [
                            'name' => 'Color',
                            'sku' => $colorSku,
                            'price' => ($product->price ?? 0) * 1.5,
                            'stock' => 0,
                            'weight' => 0,
                        ], []); // Empty attributes array
                    }

                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = "Produk ID {$productId}: " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "✅ Berhasil mengkonversi {$successCount} produk menjadi Smart Print Products!";
            
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', $errors);
            }

            return redirect()->route('admin.smart-print-converter.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.smart-print-converter.index')
                ->with('error', "❌ Gagal melakukan bulk convert: " . $e->getMessage());
        }
    }

    /**
     * Generate unique SKU for variant to avoid duplicate errors
     */
    private function generateUniqueSku($baseSku)
    {
        $originalSku = $baseSku;
        $counter = 1;

        // Check if SKU already exists in product_variants table
        while (ProductVariant::where('sku', $baseSku)->exists()) {
            $baseSku = $originalSku . '-' . $counter;
            $counter++;
        }

        return $baseSku;
    }
}