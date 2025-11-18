<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Image;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $imageUrls = [
            'https://i.ibb.co/Y4VypWXh/box15.png',
            'https://i.ibb.co/wZ2fcDjr/image.png',
            'https://i.ibb.co/x8gF0gZ6/Stamp.png',
            'https://i.ibb.co/Y4VypWXh/box15.png',
            'https://i.ibb.co/8nbCrJ3k/mug.png',
            'https://i.ibb.co/zhf1B4pz/Tape.png',
        ];

        $products = Product::whereBetween('id', [1, 24])->get();

        foreach ($products as $index => $product) {
            // نختار صورة من القائمة بشكل دائري
            $imageUrl = $imageUrls[$index % count($imageUrls)];

            $product->images()->create([
                'url' => $imageUrl,
                'alt' => 'Product Image ' . $product->id,
                'type' => 'main',
                'order' => 1,
                'is_active' => true,
            ]);
        }
    }
}
