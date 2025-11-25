<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('design_services')) {
            Schema::create('design_services', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // رفع تصميم خاص، تعديل، تصميم جديد
                $table->decimal('price', 10, 2)->default(0);
                $table->text('description')->nullable();
                $table->timestamps();
            });
            
            // إضافة خدمات التصميم الافتراضية
            DB::table('design_services')->insert([
                ['name' => 'رفع تصميم خاص', 'price' => 0, 'description' => 'رفع تصميم جاهز من العميل', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'تعديل تصميم', 'price' => 95, 'description' => 'تعديل على تصميم موجود', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'تصميم جديد', 'price' => 325, 'description' => 'إنشاء تصميم جديد من الصفر', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('design_services');
    }
};
