<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_columns_to_product_options_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToProductOptionsTable extends Migration
{
    public function up()
    {
        Schema::table('product_options', function (Blueprint $table) {

            if (!Schema::hasColumn('product_options', 'category')) {
                $table->string('category', 50)
                    ->nullable()
                    ->after('is_required');
            }

            if (!Schema::hasColumn('product_options', 'extra_data')) {
                $table->json('extra_data')
                    ->nullable()
                    ->after('category');
            }

            // احذف السطور دي لو موجودة بالفعل
            // $table->index('product_id');
            // $table->index('category');
            // $table->index(['depends_on_option_id', 'depends_on_detail_id']);
        });
    }


    public function down()
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropColumn(['category', 'extra_data']);
        });
    }
}
