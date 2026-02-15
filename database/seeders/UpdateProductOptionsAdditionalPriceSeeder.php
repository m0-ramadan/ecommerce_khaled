<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateProductOptionsAdditionalPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_options')
            ->where('option_name', 'LIKE', '%مدة التنفيذ%')
            ->where('category', 'delivery_time')
            ->update(['additional_price' => 0]);

        $this->command->info('تم تحديث additional_price بنجاح لجميع خيارات مدة التنفيذ');
    }
}
