<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'type' => $this->type,
            'price' => (float) $this->price,
            'harga_beli' => (float) $this->harga_beli,
            'status' => $this->status,
            'weight' => $this->weight,
            'short_description' => $this->short_description,
            'barcode' => $this->barcode,
            'brand_id' => $this->brand_id,
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(fn($img) => [
                    'id' => $img->id,
                    'path' => $img->path,
                    'url' => $img->path ? asset('storage/' . $img->path) : null,
                ]);
            }),
            'thumbnail' => $this->whenLoaded('images', function () {
                $first = $this->images->first();
                return $first ? asset('storage/' . $first->path) : null;
            }),
        ];

        // Stock info based on product type
        if ($this->type === 'simple') {
            $data['stock'] = $this->whenLoaded('productInventory', function () {
                return (int) ($this->productInventory->qty ?? 0);
            });
        }

        if ($this->type === 'configurable') {
            $data['variants'] = VariantResource::collection($this->whenLoaded('productVariants'));
            $data['variant_options'] = $this->when(
                $this->relationLoaded('productVariants') && $this->productVariants->count() > 0,
                function () {
                    try {
                        return $this->getVariantOptions();
                    } catch (\Exception $e) {
                        return [];
                    }
                }
            );
            $data['price_range'] = $this->when(
                $this->relationLoaded('productVariants') && $this->productVariants->count() > 0,
                function () {
                    $active = $this->productVariants->where('is_active', true);
                    if ($active->isEmpty()) return null;
                    $min = $active->min('price');
                    $max = $active->max('price');
                    return ['min' => (float) $min, 'max' => (float) $max, 'same' => $min == $max];
                }
            );
        }

        $data['created_at'] = $this->created_at?->toISOString();
        $data['updated_at'] = $this->updated_at?->toISOString();

        return $data;
    }
}
