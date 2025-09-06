<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantAttribute extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public static function getAttributeOptions($productId, $attributeName)
    {
        return self::join('product_variants', 'variant_attributes.variant_id', '=', 'product_variants.id')
            ->where('product_variants.product_id', $productId)
            ->where('variant_attributes.attribute_name', $attributeName)
            ->where('product_variants.is_active', true)
            ->distinct()
            ->pluck('variant_attributes.attribute_value')
            ->sort()
            ->values();
    }

    public static function getVariantByAttributes($productId, $attributes)
    {
        $query = ProductVariant::where('product_id', $productId)
            ->where('is_active', true);

        foreach ($attributes as $attributeName => $attributeValue) {
            $query->whereHas('variantAttributes', function ($q) use ($attributeName, $attributeValue) {
                $q->where('attribute_name', $attributeName)
                  ->where('attribute_value', $attributeValue);
            });
        }

        return $query->first();
    }
}
