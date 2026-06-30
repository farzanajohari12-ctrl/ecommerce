<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            'Sale',
            'New',
            'Hot',
            'Featured',
            'Exclusive',
            'Limited',
            'Bestseller',
            'Trending Now',
            'Clearance',
            'Top Pick',
        ];

        foreach ($badges as $name) {
            Badge::create([
                'name' => $name
            ]);
        }
    }
}