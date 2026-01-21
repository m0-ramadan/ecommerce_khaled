<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seeder_progress', function (Blueprint $table) {

            // Counters
            $table->unsignedInteger('success_count')->default(0)->after('total_processed');
            $table->unsignedInteger('fail_count')->default(0)->after('success_count');
            $table->unsignedInteger('skipped_count')->default(0)->after('fail_count');

            // Performance
            $table->float('current_memory_usage')->nullable()->after('skipped_count');
            $table->float('average_processing_time')->nullable()->after('current_memory_usage');

            // Timestamps
            $table->timestamp('started_at')->nullable()->after('status');
            $table->timestamp('completed_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('seeder_progress', function (Blueprint $table) {

            $table->dropColumn([
                'success_count',
                'fail_count',
                'skipped_count',
                'current_memory_usage',
                'average_processing_time',
                'started_at',
            ]);
        });
    }
};
