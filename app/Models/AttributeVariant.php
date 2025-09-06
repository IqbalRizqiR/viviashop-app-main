<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeVariant extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attribute_options()
    {
        return $this->hasMany(AttributeOption::class);
    }
}
