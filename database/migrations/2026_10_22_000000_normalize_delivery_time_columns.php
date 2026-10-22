<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * بعض النسخ القديمة غيّرت from_days/to_days إلى min_days/max_days،
     * بينما التطبيق وواجهة الـ API يستخدمان الاسمين الأصليين.
     */
    public function up(): void
    {
        if (Schema::hasColumn('delivery_times', 'min_days')
            && ! Schema::hasColumn('delivery_times', 'from_days')) {
            Schema::table('delivery_times', function (Blueprint $table) {
                $table->renameColumn('min_days', 'from_days');
            });
        }

        if (Schema::hasColumn('delivery_times', 'max_days')
            && ! Schema::hasColumn('delivery_times', 'to_days')) {
            Schema::table('delivery_times', function (Blueprint $table) {
                $table->renameColumn('max_days', 'to_days');
            });
        }

        if (! Schema::hasColumn('delivery_times', 'note')) {
            Schema::table('delivery_times', function (Blueprint $table) {
                $table->text('note')->nullable();
            });
        }
    }

    public function down(): void
    {
        // توحيد الأسماء إصلاح توافق، ولا نعيد كسر مخطط قاعدة البيانات عند rollback.
    }
};
