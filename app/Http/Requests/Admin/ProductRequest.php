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
                    'type' => 'required',
                    'price' => 'numeric',
                    'harga_beli' => 'numeric',
                    'qty' => 'numeric',
                    'weight' => 'numeric',
                    'status' => 'nullable',
                    'link1' => 'nullable',
                    'link2' => 'nullable',
                    'link3' => 'nullable',
                    'name' => ['required', 'max:255'],
                    'sku' => ['required', 'max:255', 'unique:products,sku'],
                    'barcode' => 'nullable|numeric|unique:products,barcode',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                if ($this->get('type') == 'simple') {
                    return [
                        'type' => 'required',
                        'price' => ['required', 'numeric'],
                        'harga_beli' => ['required', 'numeric'],
                        'qty' => ['required', 'numeric'],
                        'weight' => ['required', 'numeric'],
                        'height' => 'nullable|numeric',
                        'width' => 'nullable|numeric',
                        'length' => 'nullable|numeric',
                        'status' => 'required',
                        'link1' => 'nullable',
                        'link2' => 'nullable',
                        'link3' => 'nullable',
                        'short_description' => 'required',
                        'description' => 'required',
                        'name' => ['required', 'max:255', 'unique:products,name,'.$this->route()->product->id],
                        'sku' => ['required', 'max:255', 'unique:products,sku,'. $this->route()->product->id],
                    ];
                }else {
                    return [
                        'type' => 'required',
                        'price' => 'numeric',
                        'harga_beli' => 'numeric',
                        'qty' => 'numeric',
                        'weight' => 'numeric',
                        'status' => 'required',
                        'link1' => 'nullable',
                        'link2' => 'nullable',
                        'link3' => 'nullable',
                        'short_description' => 'required',
                        'description' => 'required',
                        'name' => ['required', 'max:255', 'unique:products,name,'. $this->route()->product->id],
                        'sku' => ['required', 'max:255', 'unique:products,sku,'. $this->route()->product->id],
                    ];
                }

            }
            default: break;
        }
    }
}
