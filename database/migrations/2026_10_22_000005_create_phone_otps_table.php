<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('phone_otps')) {
            Schema::create('phone_otps', function (Blueprint $table) {
                $table->id();
                $table->string('phone', 20)->index();
                $table->string('otp'); // hashed
                $table->unsignedTinyInteger('attempts')->default(0);
                $table->timestamp('expires_at');
                $table->timestamp('used_at')->nullable();
                $table->string('ip', 45)->nullable();
                $table->string('user_agent', 255)->nullable();
                $table->timestamps();

                $table->index(['phone', 'used_at', 'expires_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_otps');
    }
};
