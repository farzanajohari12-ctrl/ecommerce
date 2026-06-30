<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        try {

            $query = $request->get('query');

            if (!$query) {
                return response()->json([
                    'products' => [],
                    'categories' => [],
                    'orders' => [],
                    'users' => []
                ]);
            }

            return response()->json([
                'products' => Product::where('title', 'LIKE', "%{$query}%")
                    ->limit(5)
                    ->get(),

                'categories' => Category::where('name', 'LIKE', "%{$query}%")
                    ->limit(5)
                    ->get(),

                'orders' => Order::where('id', 'LIKE', "%{$query}%")
                    ->limit(5)
                    ->get(),

                'users' => User::where('name', 'LIKE', "%{$query}%")
                    ->limit(5)
                    ->get(),
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
