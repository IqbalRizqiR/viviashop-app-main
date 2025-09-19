<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintFile extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'file_size' => 'integer',
        'pages_count' => 'integer',
        'is_processed' => 'boolean',
    ];

    public function printOrder()
    {
        return $this->belongsTo(PrintOrder::class, 'print_order_id');
    }

    public function printSession()
    {
        return $this->belongsTo(PrintSession::class);
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }

    public function getFileTypeDisplayAttribute()
    {
        return strtoupper($this->file_type);
    }

    public function markAsProcessed()
    {
        $this->update(['is_processed' => true]);
    }

    public static function getSupportedTypes()
    {
        return ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'txt'];
    }

    public function isSupportedType()
    {
        return in_array(strtolower($this->file_type), self::getSupportedTypes());
    }
}
