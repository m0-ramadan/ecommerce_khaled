<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'notes_enabled')) {
                $table->boolean('notes_enabled')->default(false)->after('description');
            }
            if (! Schema::hasColumn('products', 'notes_label')) {
                $table->string('notes_label')->nullable()->after('notes_enabled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'notes_label')) {
                $table->dropColumn('notes_label');
            }
            if (Schema::hasColumn('products', 'notes_enabled')) {
                $table->dropColumn('notes_enabled');
            }
        });
    }
};
