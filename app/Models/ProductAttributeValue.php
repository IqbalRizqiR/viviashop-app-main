<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attribute_variant()
    {
        return $this->belongsTo(AttributeVariant::class);
    }

    public function attribute_option()
    {
        return $this->belongsTo(AttributeOption::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function getAttributeOptions($product, $attributeCode)
    {
        $productVariantIDs = $product->variants->pluck('id');
        $attribute = Attribute::where('code', $attributeCode)->first();
        
        if (!$attribute) {
            return collect();
        }
        
        $attributeOptions = ProductAttributeValue::where('attribute_id', $attribute->id)
                            ->whereIn('product_id', $productVariantIDs)
                            ->with(['attribute', 'attribute_variant', 'attribute_option'])
                            ->get();
        return $attributeOptions;
    }

    public static function getAttributeHierarchy($product, $attributeCode)
    {
        $productVariantIDs = $product->variants->pluck('id');
        $attribute = Attribute::where('code', $attributeCode)->first();
        
        if (!$attribute) {
            return collect();
        }
        
        return ProductAttributeValue::where('attribute_id', $attribute->id)
                    ->whereIn('product_id', $productVariantIDs)
                    ->with(['attribute', 'attribute_variant', 'attribute_option'])
                    ->get()
                    ->groupBy('attribute_variant_id');
    }
}
