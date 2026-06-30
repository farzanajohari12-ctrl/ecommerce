<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'variant_attribute_id',
        'variant_value_id'
        
    ];

    public function value()
    {
        return $this->belongsTo(VariantValue::class, 'variant_value_id');
    }

    public function attribute()
    {
        return $this->belongsTo(VariantAttribute::class, 'variant_attribute_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}