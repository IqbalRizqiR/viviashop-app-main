<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'variant_id',
        'movement_type',
        'quantity',
        'old_stock',
        'new_stock',
        'reference_type',
        'reference_id',
        'reason',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'old_stock' => 'integer',
        'new_stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    const MOVEMENT_IN = 'in';
    const MOVEMENT_OUT = 'out';

    const REASON_ORDER_CONFIRMED = 'order_confirmed';
    const REASON_ORDER_CANCELLED = 'order_cancelled';
    const REASON_PURCHASE_CONFIRMED = 'purchase_confirmed';
    const REASON_PURCHASE_CANCELLED = 'purchase_cancelled';
    const REASON_PRINT_ORDER = 'print_order';
    const REASON_MANUAL_ADJUSTMENT = 'manual_adjustment';
    const REASON_INVENTORY_CORRECTION = 'inventory_correction';
    const REASON_STOCK_SYNCHRONIZATION = 'stock_synchronization';
    const REASON_DAMAGE = 'damage';
    const REASON_RETURN = 'return';

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function printOrder()
    {
        return $this->belongsTo(PrintOrder::class, 'reference_id')->where('reference_type', 'print_order');
    }

    public function purchase()
    {
        return $this->belongsTo(Pembelian::class, 'reference_id')->where('reference_type', 'purchase');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'reference_id')->where('reference_type', 'order');
    }

    public function scopeMovementType($query, $type)
    {
        return $query->where('movement_type', $type);
    }

    public function scopeByVariant($query, $variantId)
    {
        return $query->where('variant_id', $variantId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }

    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    public function getMovementTypeColorAttribute()
    {
        return $this->movement_type === self::MOVEMENT_IN ? 'success' : 'danger';
    }

    public function getMovementTypeIconAttribute()
    {
        return $this->movement_type === self::MOVEMENT_IN ? 'fas fa-arrow-up' : 'fas fa-arrow-down';
    }

    public function getReasonLabelAttribute()
    {
        return match($this->reason) {
            self::REASON_ORDER_CONFIRMED => 'Order Confirmed',
            self::REASON_ORDER_CANCELLED => 'Order Cancelled',
            self::REASON_PURCHASE_CONFIRMED => 'Purchase Confirmed',
            self::REASON_PURCHASE_CANCELLED => 'Purchase Cancelled',
            self::REASON_PRINT_ORDER => 'Print Order',
            self::REASON_MANUAL_ADJUSTMENT => 'Manual Adjustment',
            self::REASON_INVENTORY_CORRECTION => 'Inventory Correction',
            self::REASON_DAMAGE => 'Damage/Loss',
            self::REASON_RETURN => 'Return',
            default => ucwords(str_replace('_', ' ', $this->reason))
        };
    }
}
