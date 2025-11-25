<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmbroiderLocation;

class EmbroiderLocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            ['name' => 'الجهة الأمامية (صدر)', 'additional_price' => 10],
            ['name' => 'الجهة الخلفية (ظهر)', 'additional_price' => 15],
            ['name' => 'الكتف الأيمن', 'additional_price' => 8],
            ['name' => 'الكتف الأيسر', 'additional_price' => 8],
            ['name' => 'الكم الأيمن', 'additional_price' => 7],
            ['name' => 'الكم الأيسر', 'additional_price' => 7],
            ['name' => 'الجانب الأيمن', 'additional_price' => 6],
            ['name' => 'الجانب الأيسر', 'additional_price' => 6],
            ['name' => 'الياقة', 'additional_price' => 5],
        ];

        foreach ($locations as $loc) {
            EmbroiderLocation::create($loc);
        }
    }
}
