<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => (float) $this->price,
            'harga_beli' => (float) $this->harga_beli,
            'stock' => (int) $this->stock,
            'weight' => $this->weight,
            'is_active' => (bool) $this->is_active,
            'attributes' => $this->whenLoaded('variantAttributes', function () {
                return $this->variantAttributes->map(fn($attr) => [
                    'name' => $attr->attribute_name,
                    'value' => $attr->attribute_value,
                ]);
            }),
        ];
    }
}
