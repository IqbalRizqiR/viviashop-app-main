<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\AttributeOption;
use App\Models\ProductInventory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\ProductAttributeValue;
use App\Http\Requests\Admin\ProductRequest;
use App\Imports\ProdukImport;
use Barryvdh\DomPDF\Facade\Pdf;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductTemplateExport;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('name', 'ASC')->with('productInventory')->get();

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
        $types = Product::types();
        $configurable_attributes = $this->_getConfigurableAttributes();

        return view('admin.products.create', compact('categories', 'types', 'configurable_attributes'));
    }

    private function _getConfigurableAttributes()
	{
		return Attribute::where('is_configurable', true)->get();
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

    public function downloadBarcode()
    {
        $data = Product::all();
        $pdf  = Pdf::loadView('admin.barcode', compact('data'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('barcode.pdf');
        // return view('admin.barcode', compact('data'));
    }

    public function downloadSingleBarcode($id)
    {
        $dataSingle = Product::where('id', $id)->first();
        // dd($data->barcode);
        $pdf  = Pdf::loadView('admin.barcodeSingle', compact('dataSingle'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('barcode.pdf');
        // return view('admin.barcode', compact('data'));
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
                $attributeValueParams = [
                    'parent_product_id' => $parentProductID,
                    'product_id' => $product->id,
                    'attribute_id' => $attributeOption->attribute_id,
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
        $request['barcode'] = rand(1000000000, 9999999999);
        $product = DB::transaction(
			function () use ($request) {
				$categoryIds = !empty($request['category_id']) ? $request['category_id'] : [];
                $product = Product::where('name', $request['name'])->where('parent_id', NULL)->first();
                if ($product != NULL && $request['type'] == 'configurable') {
                    $product->categories()->sync($categoryIds);

                    if ($request['type'] == 'configurable') {
                        $this->_generateProductVariants($product, $request);
                    }
                } else {
                    $product = Product::create($request->validated() + ['user_id' => auth()->id()] + ['barcode' => $request['barcode']]);
                    // suka kah
                    $product->categories()->sync($categoryIds);

                    if ($request['type'] == 'configurable') {
                        $this->_generateProductVariants($product, $request);
                    }
                }


				return $product;
			}
        );

        return redirect()->route('admin.products.edit', $product)->with([
            'message' => 'Berhasil di buat !',
            'alert-type' => 'success'
        ]);
    }

    public function data()
    {
        // 1) Fetch all products into a collection
        $products = Product::select('id', 'sku', 'name', 'price')
            ->get();


        // 3) Return a valid DataTables JSON response
        return datatables()
            ->of($products)                   // use the array of rows
            ->addIndexColumn()            // adds DT_RowIndex
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
                            data-name="'. e($product->name) .'">
                             Add
                            </button>';
            })
            ->rawColumns(['action'])      // allow HTML in the action column
            ->make(true);
    }

    public function findByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');

        $product = Product::where('barcode', $barcode)
                        ->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
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
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name', 'ASC')->get(['name','id']);
        $statuses = Product::statuses();
        $types = Product::types();
        $configurable_attributes = $this->_getConfigurableAttributes();

        return view('admin.products.edit', compact('product','categories','statuses','types','configurable_attributes'));
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

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $saved = false;
		$saved = DB::transaction(
			function () use ($product, $request) {
				$categoryIds = !empty($request['category_id']) ? $request['category_id'] : [];
                $request['barcode'] = rand(1000000000, 9999999999);
				$product->update($request->validated());
				$product->categories()->sync($categoryIds);

				if ($product->type == 'configurable') {
					$this->_updateProductVariants($request);
				} else {
					ProductInventory::updateOrCreate(['product_id' => $product->id], ['qty' => $request['qty']]);
				}

				return true;
			}
        );

        return redirect()->route('admin.products.index')->with([
            'message' => 'Berhasil di ganti !',
            'alert-type' => 'info'
        ]);
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
}
