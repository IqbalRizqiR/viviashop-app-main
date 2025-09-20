<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Models\AttributeOption;
use App\Models\ProductInventory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\ProductAttributeValue;
use App\Http\Requests\Admin\ProductRequest;
use App\Services\ProductVariantService;
use App\Imports\ProdukImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductTemplateExport;

class ProductController extends Controller
{
    protected $productVariantService;

    public function __construct(ProductVariantService $productVariantService)
    {
        $this->productVariantService = $productVariantService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['brand', 'productInventory', 'productVariants'])
            ->where('parent_id', null)
            ->orderBy('name', 'ASC')
            ->get();

        return view('admin.products.index', compact('products'));
    }

    public function exportTemplate()
    {
        return Excel::download(new ProductTemplateExport, 'template.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get(['name','id']);
        $brands = Brand::active()->orderBy('name', 'ASC')->get(['name','id']);
        $types = Product::types();
        $configurable_attributes = $this->_getConfigurableAttributes();

        return view('admin.products.create', compact('categories', 'brands', 'types', 'configurable_attributes'));
    }

    private function _getConfigurableAttributes()
	{
		return Attribute::where('is_configurable', true)->with(['attribute_variants.attribute_options'])->get();
    }

    private function _generateAttributeCombinations($arrays)
	{
        // dd($arrays);
        $result = [[]];
		foreach ($arrays as $property => $property_values) {
            $tmp = [];
			if ($property_values != null) {
                // dd(array($property => $property_values));
                foreach ($result as $result_item) {
                    foreach ($property_values as $property_value) {
                        $tmp[] = array_merge($result_item, array($property => $property_value));
                    }
                }
            } else {
                foreach ($result as $result_item) {
                    $tmp[] = array_merge($result_item, array($property => 'null'));
                }
            }

            // dd($tmp);
            $result = $tmp;
        }
		return $result;
    }

    public function previewBarcode()
    {
        $data = Product::whereNotNull('barcode')->get();
        return view('admin.barcode_preview_menu', compact('data'));
    }

    public function previewBarcodeLandscape()
    {
        $data = Product::whereNotNull('barcode')->get();
        return view('admin.barcode_preview_landscape', compact('data'));
    }

    public function previewBarcodePortrait()
    {
        $data = Product::whereNotNull('barcode')->get();
        return view('admin.barcode_preview_portrait', compact('data'));
    }

    public function printBarcodeLandscape()
    {
        $data = Product::whereNotNull('barcode')->get();
        return view('admin.barcode_landscape', compact('data'));
    }

    public function printBarcodePortrait()
    {
        $data = Product::whereNotNull('barcode')->get();
        return view('admin.barcode_portrait', compact('data'));
    }

    public function downloadSingleBarcode($id)
    {
        $dataSingle = Product::where('id', $id)->whereNotNull('barcode')->first();
        
        if (!$dataSingle) {
            Alert::error('Error', 'Produk tidak ditemukan atau barcode belum dibuat.');
            return redirect()->back();
        }
        
        $pdf = Pdf::loadView('admin.barcodeSingle', compact('dataSingle'));
        $pdf->setPaper('a4', 'landscape');
        $filename = 'barcode-' . $dataSingle->sku . '.pdf';
        return $pdf->stream($filename);
    }

    public function generateBarcodeAll()
    {
        $products = Product::whereNull('barcode')->get();
        foreach ($products as $product) {
            $barcode = rand(1000000000, 9999999999);
            $product->barcode = $barcode;
            $product->save();
        }

        Alert::success('Berhasil', 'Barcode untuk semua produk telah dibuat.');
        return redirect()->route('admin.products.index');
    }
    public function generateBarcodeSingle($id)
    {
        $products = Product::where('id', $id)->first();
        $products->barcode = rand(1000000000, 9999999999);
        $products->save();

        Alert::success('Berhasil', 'Barcode untuk produk telah dibuat.');
        return redirect()->route('admin.products.index');
    }

    private function _convertVariantAsName($variant)
	{
		$variantName = '';
		foreach (array_keys($variant) as $key => $code) {
			$attributeOptionID = $variant[$code];
			$attributeOption = AttributeOption::find($attributeOptionID);

			if ($attributeOption) {
				$variantName .= ' - ' . $attributeOption->name;
			}
		}

		return $variantName;
    }

    private function _saveProductAttributeValues($product, $variant, $parentProductID)
	{
		foreach (array_values($variant) as $attributeOptionID) {
            $attributeOption = AttributeOption::find($attributeOptionID);

			if ($attributeOption != null) {
                $attributeVariant = $attributeOption->attribute_variant;
                $attribute = $attributeVariant->attribute;
                
                $attributeValueParams = [
                    'parent_product_id' => $parentProductID,
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                    'attribute_variant_id' => $attributeVariant->id,
                    'attribute_option_id' => $attributeOption->id,
                    'text_value' => $attributeOption->name,
                ];
                ProductAttributeValue::create($attributeValueParams);
            }
		}
	}

    private function _generateProductVariants($product, $request)
	{
		$configurableAttributes = $this->_getConfigurableAttributes();

		$variantAttributes = [];
        // dd($request->all());
		foreach ($configurableAttributes as $attribute) {
            $variantAttributes[$attribute->code] = $request[$attribute->code];
        }

		$variants = $this->_generateAttributeCombinations($variantAttributes);
        // dd($variants);
        // here
		if ($variants) {
			foreach ($variants as $variant) {
				$variantParams = [
					'parent_id' => $product->id,
					'user_id' => auth()->id(),
					'sku' => $product->sku . '-' .implode('-', array_values($variant)),
					'type' => 'simple',
					'link1' => $request->link1,
					'link2' => $request->link2,
					'link3' => $request->link3,
					'name' => $product->name . $this->_convertVariantAsName($variant),
				];
                $request['barcode'] = rand(1000000000, 9999999999);
				$newProductVariant = Product::create($variantParams);

				$categoryIds = !empty($request['category_id']) ? $request['category_id'] : [];
				$newProductVariant->categories()->sync($categoryIds);

                $this->_saveProductAttributeValues($newProductVariant, $variant, $product->id);
			}
		}
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            // Ensure checkbox values are properly handled
            $data = $request->validated();
            
            // CRITICAL FIX: Force checkbox handling regardless of form submission
            $data['is_print_service'] = $request->has('is_print_service') || $request->get('is_print_service') == '1' || $request->get('is_print_service') === 'on';
            $data['is_smart_print_enabled'] = $request->has('is_smart_print_enabled') || $request->get('is_smart_print_enabled') == '1' || $request->get('is_smart_print_enabled') === 'on';
            
            if ($request->type === 'configurable' && !empty($request->variants)) {
                $result = $this->productVariantService->createConfigurableProduct(
                    $data,
                    $request->variants
                );
                $product = $result['product'];
            } else {
                $product = $this->productVariantService->createBaseProduct($data);
                
                if ($request->type === 'simple') {
                    $this->createSimpleProductInventory($product, $request);
                    
                    // Auto-create variants for smart print products
                    if ($data['is_smart_print_enabled'] && $data['is_print_service']) {
                        $this->createDefaultSmartPrintVariants($product);
                    }
                }
            }

            return redirect()->route('admin.products.edit', $product)->with([
                'message' => 'Produk berhasil dibuat!',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with([
                'message' => 'Error: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }

    private function createSimpleProductInventory(Product $product, $request)
    {
        if ($request->qty !== null) {
            ProductInventory::create([
                'product_id' => $product->id,
                'qty' => $request->qty,
            ]);
        }
    }
    
    private function createDefaultSmartPrintVariants(Product $product)
    {
        $defaultVariants = [
            [
                'name' => $product->name . ' - Black & White',
                'sku' => $product->sku . '-BW',
                'paper_size' => 'A4',
                'print_type' => 'bw',
                'stock' => 100,
                'price' => $product->price ?: 1000,
                'harga_beli' => $product->harga_beli ?: 500,
                'attributes' => [
                    'print_type' => 'Black & White',
                    'paper_size' => 'A4'
                ]
            ],
            [
                'name' => $product->name . ' - Color',
                'sku' => $product->sku . '-CLR',
                'paper_size' => 'A4', 
                'print_type' => 'color',
                'stock' => 50,
                'price' => ($product->price ?: 1000) * 1.5,
                'harga_beli' => $product->harga_beli ?: 500,
                'attributes' => [
                    'print_type' => 'Color',
                    'paper_size' => 'A4'
                ]
            ]
        ];
        
        foreach ($defaultVariants as $variantData) {
            $variant = \App\Models\ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantData['sku'],
                'name' => $variantData['name'],
                'price' => $variantData['price'],
                'harga_beli' => $variantData['harga_beli'],
                'stock' => $variantData['stock'],
                'weight' => $product->weight ?: 0.1,
                'length' => $product->length,
                'width' => $product->width,
                'height' => $product->height,
                'print_type' => $variantData['print_type'],
                'paper_size' => $variantData['paper_size'],
                'is_active' => true,
                'min_stock_threshold' => $variantData['stock'] * 0.1,
            ]);

            foreach ($variantData['attributes'] as $attrName => $attrValue) {
                \App\Models\VariantAttribute::create([
                    'variant_id' => $variant->id,
                    'attribute_name' => $attrName,
                    'attribute_value' => $attrValue,
                    'sort_order' => 0
                ]);
            }
        }
    }

    public function data()
    {
        $products = Product::with(['variants.productAttributeValues.attribute', 'variants.productAttributeValues.attribute_variant', 'variants.productAttributeValues.attribute_option'])
            ->select('id', 'sku', 'name', 'price', 'type', 'total_stock')
            ->get();

        return datatables()
            ->of($products)
            ->addIndexColumn()
            ->addColumn('name' , function ($product) {
                return $product->name;
            })
            ->addColumn('sku' , function ($product) {
                return $product->sku;
            })
            ->addColumn('price' , function ($product) {
                return $product->price;
            })
            ->addColumn('action', function ($product) {
                return'<button
                                type="button"
                             class="btn btn-sm btn-success select-product"
                             data-id="'. $product->id .'"
                             data-sku="'. $product->sku .'"
                             data-name="'. e($product->name) .'"
                             data-type="'. $product->type .'"
                             data-price="'. $product->price .'"
                             data-stock="'. $product->total_stock .'">
                             Add
                            </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getProductAttributes($id)
    {
        $product = Product::with(['variants.productAttributeValues.attribute', 'variants.productAttributeValues.attribute_variant', 'variants.productAttributeValues.attribute_option'])
            ->find($id);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($product->type !== 'configurable') {
            return response()->json(['attributes' => []]);
        }

        $configurableAttributes = \App\Models\Attribute::where('is_configurable', true)
            ->with(['attribute_variants.attribute_options'])
            ->get();

        return response()->json([
            'product' => $product,
            'attributes' => $configurableAttributes
        ]);
    }

    public function getVariantOptions($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($product->type !== 'configurable') {
            return response()->json(['success' => false, 'message' => 'Product is not configurable']);
        }

        $variantOptions = $product->getVariantOptions();

        return response()->json([
            'success' => true,
            'data' => $variantOptions
        ]);
    }

    public function getAllVariants($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        // Check if product is configurable or simple with variants (like frontend)
        $hasVariants = $product->activeVariants()->count() > 0;
        $isConfigurable = $product->type == 'configurable' || 
                         ($product->type == 'simple' && $hasVariants);

        if (!$isConfigurable) {
            return response()->json(['success' => false, 'message' => 'Product has no variants', 'data' => []]);
        }

        // Use activeVariants() like frontend and get variant's own stock
        $variants = $product->activeVariants()
            ->with(['variantAttributes'])
            ->get()
            ->map(function($variant) {
                return [
                    'id' => $variant->id,
                    'sku' => $variant->sku,
                    'name' => $variant->name,
                    'price' => $variant->price,
                    'formatted_price' => number_format($variant->price, 0, ',', '.'),
                    'stock' => $variant->stock, // Use variant's own stock column
                    'weight' => $variant->weight ?? 0,
                    'variant_attributes' => $variant->variantAttributes->map(function($attr) {
                        return [
                            'attribute_name' => $attr->attribute_name,
                            'attribute_value' => $attr->attribute_value
                        ];
                    })->toArray()
                ];
            });

        // Get variant options like frontend
        $variantOptions = [];
        try {
            $variantOptions = $product->getVariantOptions()->toArray();
        } catch (Exception $e) {
            $variantOptions = [];
        }

        return response()->json([
            'success' => true,
            'data' => $variants,
            'variantOptions' => $variantOptions
        ]);
    }

    public function findByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');

        $product = Product::where('barcode', $barcode)
                        ->select('id', 'sku', 'name', 'price', 'type', 'total_stock')
                        ->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'type' => $product->type,
                    'total_stock' => $product->total_stock,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found'
        ]);
    }

    public function imports()
    {
        Excel::import(new ProdukImport, request()->file('excelFile'));
        Alert::success('berhasil', 'berhasil');
        return redirect()->route('admin.products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, Request $request)
    {
        $product->load(['brand', 'categories']);
        
        $variantsPerPage = 3;
        $currentPage = $request->get('variant_page', 1);
        
        $productVariants = $product->productVariants()
            ->with('variantAttributes')
            ->paginate($variantsPerPage, ['*'], 'variant_page', $currentPage);
        
        $categories = Category::orderBy('name', 'ASC')->get(['name','id']);
        $brands = Brand::active()->orderBy('name', 'ASC')->get(['name','id']);
        $statuses = Product::statuses();
        $types = Product::types();
        
        $variantOptions = [];
        if ($product->type === 'configurable' && $product->productVariants && $product->productVariants->count() > 0) {
            $variantOptions = $product->getVariantOptions();
        }

        $configurable_attributes = collect();
        $selected_attributes = [];

        return view('admin.products.edit', compact(
            'product', 
            'productVariants',
            'categories', 
            'brands', 
            'statuses', 
            'types', 
            'variantOptions',
            'configurable_attributes',
            'selected_attributes'
        ));
    }

    private function _updateProductVariants($request)
	{
		if ($request['variants']) {
			foreach ($request['variants'] as $productParams) {
				$product = Product::find($productParams['id']);
				$product->update($productParams);

				$product->status = $request['status'];
				$product->save();

				ProductInventory::updateOrCreate(['product_id' => $product->id], ['qty' => $productParams['qty']]);
			}
		}
	}

	private function _updateConfigurableAttributes($product, $request)
	{
		$configurableAttributes = $this->_getConfigurableAttributes();
		$hasNewAttributes = false;

		foreach ($configurableAttributes as $attribute) {
			if (!empty($request[$attribute->code])) {
				$hasNewAttributes = true;
				break;
			}
		}

		if ($hasNewAttributes) {
			foreach ($product->variants as $variant) {
				ProductAttributeValue::where('product_id', $variant->id)->delete();
				$variant->delete();
			}

			$this->_generateProductVariants($product, $request);
		}
	}

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            DB::transaction(function () use ($product, $request) {
                $data = $request->validated();
                
                $data['is_print_service'] = $request->has('is_print_service') || $request->get('is_print_service') == '1' || $request->get('is_print_service') === 'on';
                $data['is_smart_print_enabled'] = $request->has('is_smart_print_enabled') || $request->get('is_smart_print_enabled') == '1' || $request->get('is_smart_print_enabled') === 'on';
                
                $currentType = $product->type;
                $newType = $data['type'];
                $isTypeSwitching = $currentType !== $newType;

                if ($isTypeSwitching) {
                    $this->handleProductTypeSwitch($product, $currentType, $newType, $data, $request);
                } else {
                    $product->update($data);
                    
                    if (!empty($request->category_id)) {
                        $product->categories()->sync($request->category_id);
                    }

                    if ($product->type === 'configurable' && !empty($request->variants)) {
                        $this->updateConfigurableProduct($product, $request);
                    } elseif ($product->type === 'simple') {
                        $this->updateSimpleProduct($product, $request);
                    }
                }
            });

            return redirect()->route('admin.products.index')->with([
                'message' => 'Produk berhasil diperbarui!',
                'alert-type' => 'info'
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with([
                'message' => 'Error: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }

    private function handleProductTypeSwitch(Product $product, $currentType, $newType, $data, $request)
    {
        if ($currentType === 'configurable' && $newType === 'simple') {
            $this->convertConfigurableToSimple($product, $data, $request);
        } elseif ($currentType === 'simple' && $newType === 'configurable') {
            $this->convertSimpleToConfigurable($product, $data, $request);
        }
    }

    private function convertConfigurableToSimple(Product $product, $data, $request)
    {
        $existingInventory = $product->productInventory;
        $hasExistingParentData = $product->price !== null || $product->harga_beli !== null || $existingInventory;
        
        if (!$hasExistingParentData) {
            $firstVariant = $product->productVariants()->first();
            if ($firstVariant) {
                $data['price'] = $data['price'] ?? $firstVariant->price;
                $data['harga_beli'] = $data['harga_beli'] ?? $firstVariant->harga_beli;
                $data['weight'] = $data['weight'] ?? $firstVariant->weight;
                $data['length'] = $data['length'] ?? $firstVariant->length;
                $data['width'] = $data['width'] ?? $firstVariant->width;
                $data['height'] = $data['height'] ?? $firstVariant->height;
            }
            
            if (!isset($data['qty'])) {
                $totalStock = $product->productVariants()->sum('stock');
                $data['qty'] = $totalStock > 0 ? $totalStock : 1;
            }
        } else {
            $data['price'] = $data['price'] ?? $product->price;
            $data['harga_beli'] = $data['harga_beli'] ?? $product->harga_beli;
            $data['qty'] = $data['qty'] ?? ($existingInventory ? $existingInventory->qty : 1);
        }

        $product->update($data);
        
        if (!empty($request->category_id)) {
            $product->categories()->sync($request->category_id);
        }

        foreach ($product->productVariants as $variant) {
            $variant->variantAttributes()->delete();
            $variant->delete();
        }

        ProductInventory::updateOrCreate(
            ['product_id' => $product->id],
            ['qty' => $data['qty']]
        );

        if ($data['is_smart_print_enabled'] && $data['is_print_service']) {
            $this->createDefaultSmartPrintVariants($product);
        }
    }

    private function convertSimpleToConfigurable(Product $product, $data, $request)
    {
        $hasSmartPrintVariants = $product->productVariants()
            ->whereIn('print_type', ['bw', 'color'])
            ->where('paper_size', 'A4')
            ->count() >= 2;

        if ($hasSmartPrintVariants) {
            $data['price'] = $data['price'] ?? $product->price;
            $data['harga_beli'] = $data['harga_beli'] ?? $product->harga_beli;
        } else {
            $data['price'] = $data['price'] ?? $product->price;
            $data['harga_beli'] = $data['harga_beli'] ?? $product->harga_beli;
        }

        $product->update($data);
        
        if (!empty($request->category_id)) {
            $product->categories()->sync($request->category_id);
        }

        if (!$hasSmartPrintVariants && $data['is_smart_print_enabled'] && $data['is_print_service']) {
            $this->createDefaultSmartPrintVariants($product);
        }

        if (!empty($request->variants)) {
            foreach ($request->variants as $variantData) {
                if (isset($variantData['id'])) {
                    $variant = \App\Models\ProductVariant::find($variantData['id']);
                    if ($variant) {
                        $this->productVariantService->updateProductVariant($variant, $variantData);
                    }
                } else {
                    $this->productVariantService->createSingleVariant($product, $variantData);
                }
            }
        }
        
        $this->productVariantService->updateBasePrice($product);
    }

    private function updateConfigurableProduct(Product $product, $request)
    {
        if ($request->qty !== null) {
            ProductInventory::updateOrCreate(
                ['product_id' => $product->id],
                ['qty' => $request->qty]
            );
        }

        if (isset($request->variants)) {
            foreach ($request->variants as $variantData) {
                if (isset($variantData['id'])) {
                    $variant = ProductVariant::find($variantData['id']);
                    if ($variant) {
                        $this->productVariantService->updateProductVariant($variant, $variantData);
                    }
                } else {
                    $this->productVariantService->createSingleVariant($product, $variantData);
                }
            }
        }
        
        $this->productVariantService->updateBasePrice($product);
    }

    private function updateSimpleProduct(Product $product, $request)
    {
        if ($request->qty !== null) {
            ProductInventory::updateOrCreate(
                ['product_id' => $product->id],
                ['qty' => $request->qty]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        foreach($product->productImages as $productImage) {
            File::delete('storage/' . $productImage->path);
        }
        $product->delete();

        return redirect()->back()->with([
            'message' => 'Berhasil di hapus !',
            'alert-type' => 'danger'
        ]);
    }

    public function deleteVariants($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->type !== 'configurable') {
            return response()->json(['error' => 'Produk bukan tipe configurable'], 400);
        }

        DB::transaction(function () use ($product) {
            foreach ($product->variants as $variant) {
                ProductAttributeValue::where('product_id', $variant->id)->delete();
                if ($variant->productInventory) {
                    $variant->productInventory->delete();
                }
                $variant->delete();
            }
        });

        return response()->json(['message' => 'Semua variant berhasil dihapus']);
    }
}
