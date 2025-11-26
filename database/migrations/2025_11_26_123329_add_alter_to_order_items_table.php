<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // أولاً: احذف الحقول القديمة اللي مش عايزها
           // $table->dropColumn(['print_locations_count', 'embroider_locations_count', 'options']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            // ثانيًا: أضف الحقول الجديدة بالترتيب الصحيح

            // JSON fields
            if (!Schema::hasColumn('order_items', 'print_locations')) {
                $table->json('print_locations')->nullable()->after('printing_method_id');
            }
            if (!Schema::hasColumn('order_items', 'embroider_locations')) {
                $table->json('embroider_locations')->nullable()->after('print_locations');
            }
            if (!Schema::hasColumn('order_items', 'selected_options')) {
                $table->json('selected_options')->nullable()->after('embroider_locations');
            }

            // design_service_id
            if (!Schema::hasColumn('order_items', 'design_service_id')) {
                $table->foreignId('design_service_id')
                      ->nullable()
                      ->after('selected_options')
                      ->constrained('design_services')
                      ->nullOnDelete();
            }

            // note + image_design
            if (!Schema::hasColumn('order_items', 'note')) {
                $table->text('note')->nullable()->after('is_sample');
            }
            if (!Schema::hasColumn('order_items', 'image_design')) {
                $table->string('image_design')->nullable()->after('note');
            }

            // quantity_id (اختياري)
            if (!Schema::hasColumn('order_items', 'quantity_id')) {
                $table->foreignId('quantity_id')
                      ->nullable()
                      ->after('image_design')
                      ->constrained('pricing_tiers')
                      ->nullOnDelete();
            }

            // الحقول اللي كنت عايزها (غلط تضيفها هنا، لكن لو مصرّ)
            // address_id و payment_method لازم يكونوا في orders مش order_items
            // لكن لو عايز تضيفهم مؤقتًا → لازم بالترتيب ده:

            if (!Schema::hasColumn('order_items', 'address_id')) {
                $table->foreignId('address_id')->nullable()->after('order_id');
            }
            if (!Schema::hasColumn('order_items', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('total_price');
            }

            // أخيرًا: أضف الـ foreign key بعد ما العمود بقى unsignedBigInteger و nullable
            if (!Schema::hasColumn('order_items', 'address_id_foreign')) {
               // $table->foreignId('address_id')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // حذف الـ foreign key أولاً
            $table->dropForeign(['address_id']);
            $table->dropForeign(['design_service_id']);
            $table->dropForeign(['quantity_id']);

            $table->dropColumn([
                'print_locations',
                'embroider_locations',
                'selected_options',
                'design_service_id',
                'note',
                'image_design',
                'quantity_id',
                'address_id',
                'payment_method'
            ]);

            // إرجاع الحقول القديمة
            $table->integer('print_locations_count')->default(1);
            $table->integer('embroider_locations_count')->default(0);
            $table->json('options')->nullable();
        });
    }
};