<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSizeTier;
use App\Models\Size;

class ProductSizeTierSeeder extends Seeder
{
    public function run(): void
    {
        // نجيب كل الـ sizes اللي للمنتجات من 1 إلى 24
        $sizes = Size::whereBetween('product_id', [1, 24])->get();

        foreach ($sizes as $size) {

            // Define tiers (عدل الأسعار لو عايز)
            $tiers = [
                ['quantity' => 10,  'price_per_unit' => 100],
                ['quantity' => 50,  'price_per_unit' => 90],
                ['quantity' => 100, 'price_per_unit' => 80],
            ];

            foreach ($tiers as $tier) {
                ProductSizeTier::updateOrCreate(
                    [
                        'product_id' => $size->product_id,
                        'size_id' => $size->id,
                        'quantity' => $tier['quantity'],
                    ],
                    [
                        'price_per_unit' => $tier['price_per_unit'],
                    ]
                );
            }
        }
    }
}
