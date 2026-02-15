<?php

namespace Database\Seeders;

use App\Models\ProductOptions;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixOptionDependenciesSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {

            // جلب كل الخيارات
            $options = ProductOptions::all();

            // خريطة id خارجي -> id داخلي
            $externalToInternal = $options
                ->filter(fn($opt) => $opt->external_option_id)
                ->pluck('id', 'external_option_id')
                ->toArray();

            foreach ($options as $option) {
                if ($option->depends_on_option_id) {
                    // لو الـ depends_on_option_id موجود وكان على external id
                    if (isset($externalToInternal[$option->depends_on_option_id])) {
                        $internalId = $externalToInternal[$option->depends_on_option_id];

                        $option->update([
                            'depends_on_option_id' => $internalId,
                        ]);

                        $this->command->info("Option ID {$option->id} updated depends_on_option_id to {$internalId}");
                    }
                }
            }
        });

        $this->command->info("All option dependencies updated successfully.");
    }
}
