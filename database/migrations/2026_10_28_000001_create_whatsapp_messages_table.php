<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('whatsapp_messages')) {
            return;
        }

        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->string('to_phone', 20);
            $table->string('wamid')->nullable()->unique();
            $table->string('template')->nullable();

            // pending_delivery → delivered / read / failed
            $table->string('status', 30)->default('pending_delivery');
            // آخر حالة خام من ميتا (sent/accepted/delivered/read/failed)
            $table->string('meta_status', 30)->nullable();

            $table->string('error_code', 20)->nullable();
            $table->text('error_message')->nullable();

            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('to_phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
