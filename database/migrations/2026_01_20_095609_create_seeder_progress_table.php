<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seeder_progress', function (Blueprint $table) {
            $table->id();
            $table->string('seeder_name');
            $table->string('last_processed_id')->nullable();
            $table->integer('last_processed_page')->default(1);
            $table->integer('total_processed')->default(0);
            $table->enum('status', ['pending', 'in_progress', 'paused', 'completed', 'failed'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('seeder_name');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seeder_progress');
    }
};