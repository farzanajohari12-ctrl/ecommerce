<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;

class Product extends Model
{
    protected $fillable = ['title', 'description', 'price', 'sale_price', 'stock_quantity', 'category_id'];

    public function images() {
        return $this->hasMany(ProductImage::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'product_badges');
    }

    public function variants() {
        return $this->hasMany(ProductVariant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

