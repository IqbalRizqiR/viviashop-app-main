<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'waktu' => 'datetime',
        'total_item' => 'integer',
        'total_harga' => 'integer',
        'diskon' => 'integer',
        'bayar' => 'integer',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public function details()
    {
        return $this->hasMany(PembelianDetail::class, 'id_pembelian', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'reference_id', 'id')
                    ->where('reference_type', 'purchase');
    }

    public function scopePending($query)
    {
        return $query->whereNull('waktu');
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('waktu');
    }

    public function getTotalHargaAfterDiskonAttribute()
    {
        $total = $this->total_harga;
        if ($this->diskon > 0) {
            $total = $total - ($total * $this->diskon / 100);
        }
        return $total;
    }

    public function getStatusAttribute()
    {
        if (is_null($this->waktu)) {
            return self::STATUS_PENDING;
        }
        return self::STATUS_COMPLETED;
    }
}
