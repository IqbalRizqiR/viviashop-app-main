<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PrintOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'file_data' => 'array',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'uploaded_at' => 'datetime',
        'printed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    const STATUS_PENDING_UPLOAD = 'pending_upload';
    const STATUS_UPLOADED = 'uploaded';
    const STATUS_PAYMENT_PENDING = 'payment_pending';
    const STATUS_PAYMENT_CONFIRMED = 'payment_confirmed';
    const STATUS_READY_TO_PRINT = 'ready_to_print';
    const STATUS_PRINTING = 'printing';
    const STATUS_PRINTED = 'printed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_WAITING = 'waiting';
    const PAYMENT_PAID = 'paid';

    public static function generateCode()
    {
        $now = Carbon::now();
        $dateCode = 'PRINT-' . $now->format('d') . '-' . $now->format('m') . '-' . $now->format('Y') . '-' . $now->format('H') . '-' . $now->format('i') . '-' . $now->format('s');

        if (self::where('order_code', $dateCode)->exists()) {
            sleep(1);
            return self::generateCode();
        }

        return $dateCode;
    }

    public function paperProduct()
    {
        return $this->belongsTo(Product::class, 'paper_product_id');
    }

    public function paperVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'paper_variant_id');
    }

    public function session()
    {
        return $this->belongsTo(PrintSession::class, 'session_id');
    }

    public function files()
    {
        return $this->hasMany(PrintFile::class, 'print_order_id');
    }

    public function isPaid()
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function canPrint()
    {
        return $this->isPaid() && in_array($this->status, [
            self::STATUS_PAYMENT_CONFIRMED,
            self::STATUS_READY_TO_PRINT
        ]);
    }

    public function scopeReadyToPrint($query)
    {
        return $query->where('status', self::STATUS_PAYMENT_CONFIRMED)
                    ->where('payment_status', self::PAYMENT_PAID);
    }

    public function scopePrintQueue($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PAYMENT_CONFIRMED,
            self::STATUS_READY_TO_PRINT,
            self::STATUS_PRINTING
        ])->where('payment_status', self::PAYMENT_PAID);
    }

    public function markAsUploaded()
    {
        $this->update([
            'status' => self::STATUS_UPLOADED,
            'uploaded_at' => now()
        ]);
    }

    public function markAsPaymentPending()
    {
        $this->update([
            'status' => self::STATUS_PAYMENT_PENDING,
            'payment_status' => self::PAYMENT_UNPAID
        ]);
    }

    public function markAsPaymentConfirmed()
    {
        $this->update([
            'status' => self::STATUS_PAYMENT_CONFIRMED,
            'payment_status' => self::PAYMENT_PAID
        ]);
    }

    public function markAsPrinting()
    {
        $this->update([
            'status' => self::STATUS_PRINTING
        ]);
    }

    public function markAsPrinted()
    {
        $this->update([
            'status' => self::STATUS_PRINTED,
            'printed_at' => now()
        ]);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now()
        ]);
    }
}
