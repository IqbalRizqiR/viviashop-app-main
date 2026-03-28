<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'variant_id', 'qty', 'price', 'options'];

    protected $casts = [
        'options' => 'array',
        'price' => 'decimal:2',
        'qty' => 'integer',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->price * $this->qty;
    }

    public function getNameAttribute()
    {
        if ($this->variant) {
            return $this->variant->name;
        }
        return $this->product->name ?? 'Unknown Product';
    }
}
