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
        // في migration جديدة أو تعديل migration موجودة
        Schema::table('seeder_progress', function (Blueprint $table) {
            $table->text('last_cursor_url')->nullable()->after('last_processed_id');
            $table->integer('pages_processed')->default(0)->after('skipped_count');
            $table->integer('total_pages')->default(0)->after('pages_processed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
