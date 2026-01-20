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
        // في migration جديدة
Schema::table('seeder_progress', function (Blueprint $table) {
    if (!Schema::hasColumn('seeder_progress', 'last_cursor_url')) {
        $table->text('last_cursor_url')->nullable()->after('last_processed_id');
    }
    if (!Schema::hasColumn('seeder_progress', 'pages_processed')) {
        $table->integer('pages_processed')->default(0)->after('skipped_count');
    }
    if (!Schema::hasColumn('seeder_progress', 'total_pages')) {
        $table->integer('total_pages')->default(0)->after('pages_processed');
    }
    if (!Schema::hasColumn('seeder_progress', 'current_memory_usage')) {
        $table->bigInteger('current_memory_usage')->default(0)->after('total_pages');
    }
    if (!Schema::hasColumn('seeder_progress', 'average_processing_time')) {
        $table->decimal('average_processing_time', 8, 2)->default(0)->after('current_memory_usage');
    }
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
