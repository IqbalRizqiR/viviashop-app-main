<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'price' => 'decimal:2',
        'harga_beli' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantAttributes()
    {
        return $this->hasMany(VariantAttribute::class, 'variant_id');
    }

    public function attributes()
    {
        return $this->variantAttributes();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    public function productInventory()
    {
        return $this->hasOne(ProductInventory::class, 'product_id', 'product_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock <= min_stock_threshold');
    }

    public function getAttributeByName($attributeName)
    {
        return $this->variantAttributes()
            ->where('attribute_name', $attributeName)
            ->first()?->attribute_value;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format((float)$this->price, 0, ',', '.');
    }

    public function isLowStock()
    {
        return $this->stock <= $this->min_stock_threshold;
    }

    public function generateSku($product, $attributes)
    {
        $productCode = strtoupper(substr($product->name, 0, 3));
        $attributeParts = [];
        
        foreach ($attributes as $name => $value) {
            $attributeParts[] = strtoupper(substr($value, 0, 2));
        }
        
        return $productCode . '-' . implode('-', $attributeParts) . '-' . str_pad($this->id ?? rand(1, 999), 3, '0', STR_PAD_LEFT);
    }
}
