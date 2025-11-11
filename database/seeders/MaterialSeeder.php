<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materialsData = [
            ['name' => 'خشب', 'description' => 'خشب طبيعي عالي الجودة'],
            ['name' => 'معدن', 'description' => 'معدن مقاوم للصدأ'],
            ['name' => 'بلاستيك', 'description' => 'بلاستيك مقوى'],
            ['name' => 'زجاج', 'description' => 'زجاج مقاوم للكسر'],
            ['name' => 'قماش', 'description' => 'قماش قطني ناعم'],
            ['name' => 'جلد', 'description' => 'جلد صناعي متين'],
            ['name' => 'ألمنيوم', 'description' => 'ألمنيوم خفيف الوزن'],
            ['name' => 'فوم', 'description' => 'فوم عالي الكثافة'],
        ];

        $materials = collect();

        foreach ($materialsData as $data) {
            $materials->push(Material::firstOrCreate(['name' => $data['name']], $data));
        }

        $products = Product::whereBetween('id', [1, 24])->get();

        foreach ($products as $product) {
            $selected = $materials->random(rand(2, 4));

            foreach ($selected as $material) {
                $product->materials()->syncWithoutDetaching([
                    $material->id => [
                        'quantity' => rand(1, 10),
                        'unit' => 'قطعة',
                    ]
                ]);
            }
        }

        $this->command->info('✅ Materials seeded and linked to products successfully!');
    }
}
