<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('favourites', function (Blueprint $table) {
            $table->id();

            // المستخدم اللي ضاف المنتج للمفضلة
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // المنتج اللي تم تفضيله
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // ممكن تضيف نوع (لو عندك أكثر من نوع منتج)
            $table->string('type')->nullable();

            // منع التكرار (نفس المستخدم يضيف نفس المنتج مرة واحدة فقط)
            $table->unique(['user_id', 'product_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favourites');
    }
};
