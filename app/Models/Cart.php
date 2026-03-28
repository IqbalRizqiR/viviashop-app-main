<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->items->sum(fn($item) => $item->price * $item->qty);
    }

    public function getTotalItemsAttribute()
    {
        return $this->items->sum('qty');
    }

    public function getTotalWeightAttribute()
    {
        return $this->items->sum(function ($item) {
            $weight = $item->options['weight'] ?? 0;
            return $weight > 0 ? $weight * $item->qty : 100 * $item->qty; // Default 100g
        });
    }

    public static function getOrCreateForUser($userId)
    {
        return self::firstOrCreate(['user_id' => $userId]);
    }
}
