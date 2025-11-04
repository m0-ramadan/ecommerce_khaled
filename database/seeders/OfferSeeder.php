<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class OfferSeeder extends Seeder
{
    public function run()
    {
        $offers = [
            [
                'name' => 'عروض الصيف الكبرى',
                'products_count' => 10
            ],
            [
                'name' => 'تخفيضات نهاية الموسم',
                'products_count' => 15
            ],
            [
                'name' => 'عروض الجمعة البيضاء',
                'products_count' => 20
            ],
            [
                'name' => 'عروض رمضان',
                'products_count' => 12
            ],
            [
                'name' => 'عروض العودة للمدارس',
                'products_count' => 8
            ],
        ];

        foreach ($offers as $offerData) {
            $offer = Offer::create([
                'name' => $offerData['name'],
                'image' => 'offers/' . time() . '.jpg',
            ]);

            $products = Product::where('has_discount', true)
                ->inRandomOrder()
                ->limit($offerData['products_count'])
                ->get();

            $offer->products()->attach($products->pluck('id'));
        }
    }
}