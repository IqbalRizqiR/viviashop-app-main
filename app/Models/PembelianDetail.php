<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'harga_beli' => 'integer',
        'jumlah' => 'integer',
        'subtotal' => 'integer',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    public function getFormattedHargaBeliAttribute()
    {
        return 'Rp. ' . number_format($this->harga_beli, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp. ' . number_format($this->subtotal, 0, ',', '.');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->subtotal = $model->harga_beli * $model->jumlah;
        });
    }
}
