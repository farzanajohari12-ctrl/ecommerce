<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Fashion',
            'Home & Living',
            'Beauty & Health',
            'Sports & Outdoor',
            'Food & Beverages',
            'Automotive',
            'Books & Stationery',
            'Toys & Games',
            'Computer & Accessories',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name
            ]);
        }
    }
}