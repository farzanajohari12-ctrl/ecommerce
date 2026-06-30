<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VariantAttribute;

class VariantAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            'Size',          // S, M, L, XL, XXL
            'Color',         // Red, Blue, Black
            'Material',      // Cotton, Polyester, Denim, Silk
            'Fit Type',      // Slim Fit, Regular, Oversized
            'Sleeve Length', // Short Sleeve, Long Sleeve
            'Pattern',       // Plain, Striped, Printed
            'Gender',        // Men, Women, Unisex
        ];

        foreach ($attributes as $name) {
            VariantAttribute::create([
                'name' => $name
            ]);
        }
    }
}