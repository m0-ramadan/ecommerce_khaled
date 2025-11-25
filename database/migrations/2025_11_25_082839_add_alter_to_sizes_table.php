<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
    {
        Schema::table('sizes', function (Blueprint $table) {
            // تغيير value إلى name
            if (Schema::hasColumn('sizes', 'value')) {
                $table->renameColumn('value', 'name');
            }
            
            // إضافة additional_price
            if (!Schema::hasColumn('sizes', 'additional_price')) {
                $table->decimal('additional_price', 10, 2)->default(0)->after('name');
            }
            
            // إضافة stock
            if (!Schema::hasColumn('sizes', 'stock')) {
                $table->integer('stock')->default(0)->after('additional_price');
            }
        });
    }

    public function down()
    {
        Schema::table('sizes', function (Blueprint $table) {
            $table->renameColumn('name', 'value');
            $table->dropColumn(['additional_price', 'stock']);
        });
    }
};
