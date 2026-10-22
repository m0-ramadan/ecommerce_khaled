<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds the production type used by the dashboard to drive delivery time:
     * - offset  => 10 - 15 business days
     * - digital => 4 - 8 business days
     *
     * The duration always starts from the design approval date (تعميد التصميم).
     */
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'production_type')) {
            Schema::table('products', function (Blueprint $table) {
                $table->enum('production_type', ['offset', 'digital'])
                    ->default('digital')
                    ->after('stock')
                    ->index();
            });
        }

        $note = 'المدة من تاريخ تعميد التصميم';

        // استنتاج نوع الإنتاج من المدة القديمة، ثم توحيد المدد حسب النوع.
        DB::table('products')->orderBy('id')->chunkById(200, function ($products) use ($note) {
            foreach ($products as $product) {
                $delivery = DB::table('delivery_times')->where('product_id', $product->id)->first();

                $productionType = 'digital';
                if ($delivery && (int) $delivery->to_days >= 15) {
                    $productionType = 'offset';
                }

                DB::table('products')->where('id', $product->id)->update([
                    'production_type' => $productionType,
                ]);

                $range = $productionType === 'offset'
                    ? ['from_days' => 10, 'to_days' => 15]
                    : ['from_days' => 4, 'to_days' => 8];

                DB::table('delivery_times')->updateOrInsert(
                    ['product_id' => $product->id],
                    array_merge($range, [
                        'note' => $note,
                        'created_at' => $delivery->created_at ?? now(),
                        'updated_at' => now(),
                    ])
                );
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'production_type')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex(['production_type']);
                $table->dropColumn('production_type');
            });
        }
    }
};
