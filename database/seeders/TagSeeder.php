<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'New Arrival',
            'Best Seller',
            'Discount',
            'Trending',
            'Limited Edition',
            'Hot Deal',
            'Premium',
            'Eco Friendly',
            'Handmade',
            'Imported',
            'Organic',
            'Vegan',
            'Flash Sale',
            'Top Rated',
        ];

        foreach ($tags as $name) {
            Tag::create([
                'name' => $name
            ]);
        }
    }
}