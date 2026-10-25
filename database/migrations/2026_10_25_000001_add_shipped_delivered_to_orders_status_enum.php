<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $statuses = [
        'pending',
        'processing',
        'shipped',
        'completed',
        'delivered',
        'cancelled',
        'refunded',
    ];

    public function up(): void
    {
        $column = DB::selectOne("SHOW COLUMNS FROM `orders` LIKE 'status'");

        if (! $column) {
            return;
        }

        $missing = [];
        foreach ($this->statuses as $status) {
            if (! str_contains((string) $column->Type, "'{$status}'")) {
                $missing[] = $status;
            }
        }

        if ($missing !== []) {
            $enum = "'" . implode("','", $this->statuses) . "'";
            DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM({$enum}) NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        $legacy = ['pending', 'processing', 'completed', 'cancelled'];

        DB::table('orders')->whereIn('status', ['shipped', 'delivered', 'refunded'])
            ->update(['status' => 'completed']);

        $enum = "'" . implode("','", $legacy) . "'";
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM({$enum}) NOT NULL DEFAULT 'pending'");
    }
};
