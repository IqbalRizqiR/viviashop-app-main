<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        switch ($this->method()) {
            case 'POST':
            {
                return [
                    'type' => 'required|in:simple,configurable',
                    'name' => ['required', 'max:255'],
                    'sku' => ['required', 'max:255', 'unique:products,sku'],
                    'brand_id' => 'nullable|exists:brands,id',
                    'price' => 'nullable|numeric',
                    'harga_beli' => 'nullable|numeric',
                    'qty' => 'nullable|numeric',
                    'weight' => 'required|numeric',
                    'length' => 'nullable|numeric',
                    'width' => 'nullable|numeric',
                    'height' => 'nullable|numeric',
                    'status' => 'nullable|in:0,1,2',
                    'link1' => 'nullable|url',
                    'link2' => 'nullable|url',
                    'link3' => 'nullable|url',
                    'barcode' => 'nullable',
                    'short_description' => 'nullable|string',
                    'description' => 'nullable|string',
                    'is_featured' => 'nullable|boolean',
                    'is_print_service' => 'nullable|boolean',
                    'is_smart_print_enabled' => 'nullable|boolean',
                    'category_id' => 'nullable|array',
                    'category_id.*' => 'exists:categories,id',
                    'variants' => 'nullable|array',
                    'variants.*.price' => 'required_with:variants|numeric|min:0',
                    'variants.*.harga_beli' => 'nullable|numeric|min:0',
                    'variants.*.stock' => 'required_with:variants|integer|min:0',
                    'variants.*.attributes' => 'required_with:variants|array',
                    'variants.*.attributes.*' => 'required|string',
                    'variants.*.min_stock_threshold' => 'nullable|integer|min:0',
                    'variants.*.is_active' => 'nullable|boolean',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                if ($this->get('type') == 'simple') {
                    return [
                        'type' => 'required|in:simple,configurable',
                        'name' => ['required', 'max:255', 'unique:products,name,'.$this->route()->product->id],
                        'sku' => ['required', 'max:255', 'unique:products,sku,'. $this->route()->product->id],
                        'brand_id' => 'nullable|exists:brands,id',
                        'price' => ['required', 'numeric'],
                        'harga_beli' => ['required', 'numeric'],
                        'qty' => ['required', 'numeric'],
                        'weight' => ['required', 'numeric'],
                        'height' => 'nullable|numeric',
                        'width' => 'nullable|numeric',
                        'length' => 'nullable|numeric',
                        'status' => 'required|in:0,1,2',
                        'link1' => 'nullable|url',
                        'link2' => 'nullable|url',
                        'link3' => 'nullable|url',
                        'short_description' => 'required',
                        'description' => 'nullable',
                        'barcode' => 'nullable',
                        'is_featured' => 'nullable|boolean',
                        'is_print_service' => 'nullable|boolean',
                        'is_smart_print_enabled' => 'nullable|boolean',
                        'category_id' => 'nullable|array',
                        'category_id.*' => 'exists:categories,id',
                    ];
                } else {
                    return [
                        'type' => 'required|in:simple,configurable',
                        'name' => ['required', 'max:255', 'unique:products,name,'. $this->route()->product->id],
                        'sku' => ['required', 'max:255', 'unique:products,sku,'. $this->route()->product->id],
                        'brand_id' => 'nullable|exists:brands,id',
                        'price' => 'nullable|numeric',
                        'harga_beli' => 'nullable|numeric',
                        'qty' => 'nullable|numeric',
                        'weight' => 'nullable|numeric',
                        'length' => 'nullable|numeric',
                        'width' => 'nullable|numeric',
                        'height' => 'nullable|numeric',
                        'status' => 'required|in:0,1,2',
                        'link1' => 'nullable|url',
                        'link2' => 'nullable|url',
                        'link3' => 'nullable|url',
                        'short_description' => 'required',
                        'description' => 'nullable',
                        'barcode' => 'nullable',
                        'is_featured' => 'nullable|boolean',
                        'is_print_service' => 'nullable|boolean',
                        'is_smart_print_enabled' => 'nullable|boolean',
                        'category_id' => 'nullable|array',
                        'category_id.*' => 'exists:categories,id',
                        'variants' => 'nullable|array',
                        'variants.*.price' => 'required_with:variants|numeric|min:0',
                        'variants.*.harga_beli' => 'nullable|numeric|min:0',
                        'variants.*.stock' => 'required_with:variants|integer|min:0',
                        'variants.*.attributes' => 'nullable|array',
                        'variants.*.attributes.*' => 'nullable|string',
                        'variants.*.min_stock_threshold' => 'nullable|integer|min:0',
                        'variants.*.is_active' => 'nullable|boolean',
                    ];
                }
            }
            default: 
                return [];
        }
    }
}