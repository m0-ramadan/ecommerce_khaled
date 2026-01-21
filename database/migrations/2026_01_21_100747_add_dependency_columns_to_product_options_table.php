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
 // في migration file
Schema::table('product_options', function (Blueprint $table) {
    $table->unsignedBigInteger('depends_on_option_id')->nullable()->after('is_required');
    $table->unsignedBigInteger('depends_on_detail_id')->nullable()->after('depends_on_option_id');
    $table->string('dependency_condition')->nullable()->after('depends_on_detail_id');
    
    $table->foreign('depends_on_option_id')->references('id')->on('product_options')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropForeign(['depends_on_option_id']);
            $table->dropColumn(['depends_on_option_id', 'depends_on_detail_id', 'dependency_condition']);
        });
    }
};
