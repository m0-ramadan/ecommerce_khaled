<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('important_links', function (Blueprint $table) {
          $table->string('key')->unique(); // مفتاح فريد، مثال: about_us, contact, privacy_policy
            $table->string('name'); // اسم الرابط المعروض للمستخدم
            $table->text('description')->nullable(); // وصف اختياري للرابط
            $table->string('url'); // الرابط الفعلي
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('important_links', function (Blueprint $table) {
            //
        });
    }
};
