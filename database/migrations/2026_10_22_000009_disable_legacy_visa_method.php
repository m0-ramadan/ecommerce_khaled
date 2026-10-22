<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // "فيزا" القديمة كانت بلا بوابة فعلية (key = 1) وتسبب فشل الدفع.
        DB::table('payment_methods')
            ->where('key', '1')
            ->update(['is_active' => false, 'updated_at' => now()]);
    }

    public function down(): void
    {
    }
};
