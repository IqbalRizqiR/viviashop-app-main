<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getProductName()
    {
        if ($this->variant_id && $this->productVariant) {
            return $this->productVariant->name;
        }
        
        return $this->product?->name;
    }

    public function getProductPrice()
    {
        if ($this->variant_id && $this->productVariant) {
            return $this->productVariant->price;
        }
        
        return $this->product?->price;
    }

    public function getProductSku()
    {
        if ($this->variant_id && $this->productVariant) {
            return $this->productVariant->sku;
        }
        
        return $this->product?->sku;
    }
}
