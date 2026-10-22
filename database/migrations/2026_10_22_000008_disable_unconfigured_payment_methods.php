<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * These legacy methods (Visa/Mastercard/Fawry/Vodafone Cash/InstaPay/PayPal)
     * have no gateway key and would fail at checkout. The working methods are:
     * cash, paymob (cards), tabby and tamara.
     */
    public function up(): void
    {
        DB::table('payment_methods')
            ->where(function ($query) {
                $query->whereNull('key')->orWhere('key', '');
            })
            ->update(['is_active' => false, 'updated_at' => now()]);
    }

    public function down(): void
    {
        // لا نعيد تفعيل الطرق غير المهيأة.
    }
};
