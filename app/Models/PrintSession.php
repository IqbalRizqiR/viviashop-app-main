<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PrintSession extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'is_active' => 'boolean',
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    const STEP_UPLOAD = 'upload';
    const STEP_SELECT = 'select';
    const STEP_PAYMENT = 'payment';
    const STEP_PRINT = 'print';
    const STEP_COMPLETE = 'complete';

    public static function generateNew()
    {
        $sessionToken = Str::random(32);
        $barcodeToken = Str::random(32);
        
        while (self::where('session_token', $sessionToken)->exists()) {
            $sessionToken = Str::random(32);
        }
        
        while (self::where('barcode_token', $barcodeToken)->exists()) {
            $barcodeToken = Str::random(32);
        }

        return self::create([
            'session_token' => $sessionToken,
            'barcode_token' => $barcodeToken,
            'is_active' => true,
            'current_step' => self::STEP_UPLOAD,
            'started_at' => now(),
            'expires_at' => now()->addHours(24),
        ]);
    }

    public function printOrders()
    {
        return $this->hasMany(PrintOrder::class, 'session_id');
    }

    public function printFiles()
    {
        return $this->hasMany(PrintFile::class);
    }

    public function isExpired()
    {
        return $this->expires_at < now();
    }

    public function isActive()
    {
        return $this->is_active && !$this->isExpired();
    }

    public function markInactive()
    {
        $this->update(['is_active' => false]);
    }

    public function updateStep($step)
    {
        $this->update(['current_step' => $step]);
    }

    public function getQrCodeUrl()
    {
        return url('/print-service/' . $this->session_token);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('expires_at', '>', now());
    }

    public static function cleanup()
    {
        self::where('expires_at', '<', now()->subHours(1))
            ->update(['is_active' => false]);
    }
}
