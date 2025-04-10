<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasOne(Product::class, 'id', 'id_produk');
    }

    public function parent()
    {
        return $this->hasOne(Pembelian::class);
    }
}
