<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use DB;

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

                    // MAIN IMAGE
                    'image' => optional($p->images->first())->image_path ?? 'assets/no-image.png',

                    // 🔥 ALL IMAGES (IMPORTANT FIX)
                    'images' => $p->images->map(function ($img) {
                        return $img->image_path;
                    })->values()
                ];
            })
        ]);
    }

    public function getVariants($id)
    {
        try {

            $data = DB::table('product_variants')
                ->join('variant_values', 'product_variants.variant_value_id', '=', 'variant_values.id')
                ->join('variant_attributes', 'product_variants.variant_attribute_id', '=', 'variant_attributes.id')
                ->where('product_variants.product_id', $id)
                ->select(
                    'variant_attributes.name as attribute_name',
                    'variant_values.id as value_id',
                    'variant_values.value as value_name'
                )
                ->get();

            $grouped = [];

            foreach ($data as $row) {
                $grouped[$row->attribute_name][] = [
                    'value_id' => $row->value_id,
                    'value_name' => $row->value_name
                ];
            }

            return response()->json([
                'variants' => $grouped
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
