<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'variant_id' => $this->variant_id,
            'name' => $this->name,
            'qty' => (int) $this->qty,
            'price' => (float) $this->price,
            'subtotal' => (float) $this->subtotal,
            'options' => $this->options ?? [],
            'product' => new ProductResource($this->whenLoaded('product')),
            'variant' => new VariantResource($this->whenLoaded('variant')),
        ];
    }
}
