<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::whereNull('slug')->orWhere('slug', '')->get();

        foreach ($products as $product) {
            $product->slug = Product::generateUniqueSlug($product->name);
            $product->save();
            $this->command->info("Generated slug for Product ID {$product->id}: {$product->slug}");
        }
    }
}
