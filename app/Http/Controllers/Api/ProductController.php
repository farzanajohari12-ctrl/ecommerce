<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function apiProducts()
    {
        $products = Product::with('images')->latest()->get();

        return response()->json([
            'data' => $products->map(function ($p) {

                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'price' => $p->price,
                    'sale_price' => $p->sale_price,
                    'description' => $p->description,

                    // FIRST IMAGE ONLY (for carousel)
                    'image' => $p->images->first()
                        ? $p->images->first()->image_path
                        : 'assets/no-image.png',
                ];
            })
        ]);
    }
}
