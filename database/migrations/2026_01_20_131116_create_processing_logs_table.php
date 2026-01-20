<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('processing_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seeder_progress_id')->nullable()->constrained('seeder_progress')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->string('external_product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('step'); // مثل: fetch, extract, ai_process, save
            $table->enum('status', ['started', 'success', 'failed', 'skipped'])->default('started');
            $table->json('details')->nullable(); // تفاصيل الخطوة
            $table->integer('options_count')->default(0);
            $table->float('processing_time')->default(0); // بالثواني
            $table->integer('memory_usage')->default(0); // بالبايت
            $table->timestamps();
            
            $table->index(['seeder_progress_id', 'status']);
            $table->index(['product_id', 'step']);
            $table->index('created_at');
        });
        
        // إضافة محسنات للأداء
        Schema::table('processing_logs', function (Blueprint $table) {
            $table->index('step');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('processing_logs');
    }
};