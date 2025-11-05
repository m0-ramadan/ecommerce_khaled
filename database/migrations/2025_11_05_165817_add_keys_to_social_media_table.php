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
        Schema::table('social_media', function (Blueprint $table) {
                  $table->string('key')->unique(); // مثلاً: facebook, instagram, twitter
            $table->string('value')->nullable(); // رابط الحساب أو اسم المستخدم
            $table->string('icon')->nullable(); // اسم الأيقونة (مثلاً: fa-facebook)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_media', function (Blueprint $table) {
            //
        });
    }
};
