<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductPriceTextSeeder extends Seeder
{
    public function run(): void
    {
        $formats = [
            'يبدأ من %s ريال',
            'أقل من %s ريال',
            'بسعر %s ريال',
            'ابتداءً من %s ريال',
            '%s ريال فقط',
        ];

        Product::whereNotNull('price')->each(function ($product) use ($formats) {

            $format = collect($formats)->random();

            // تنسيق السعر بدون كسور لو حابب
            $price = number_format($product->price, 0);

            $product->update([
                'price_text' => sprintf($format, $price),
            ]);
        });
    }
}
