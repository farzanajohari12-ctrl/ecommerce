<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantAttribute extends Model
{
    protected $fillable = ['name'];

    public function values()
    {
        return $this->hasMany(VariantValue::class, 'variant_attribute_id');
    }
}
