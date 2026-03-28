<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'variant_id' => $this->variant_id,
            'name' => $this->name ?? $this->getProductName(),
            'sku' => $this->sku ?? $this->getProductSku(),
            'qty' => (int) $this->qty,
            'base_price' => (float) $this->base_price,
            'base_total' => (float) $this->base_total,
            'tax_amount' => (float) $this->tax_amount,
            'discount_amount' => (float) $this->discount_amount,
            'sub_total' => (float) $this->sub_total,
            'type' => $this->type,
            'weight' => $this->weight,
            'attributes' => $this->attributes ? json_decode($this->attributes, true) : [],
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
