<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * أعمدة صور القسم كانت NOT NULL وبقيمة افتراضية صورة ثابتة من imgbb،
     * فأي قسم جديد كان بياخد الصورة دي بدل ما تكون فاضية، والموقع كان
     * بيعرضها بدل الصورة المرفوعة. هنا نخليها nullable ونشيل القيمة الافتراضية.
     */
    protected array $placeholderUrls = [
        'https://i.ibb.co/20wDxXg9/3.png',
        'https://i.ibb.co/rffyqbmk/1.png',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('categories')) {
            return;
        }

        // MySQL: نجعل الأعمدة تقبل NULL الأول، وبعدين نمسح الـ placeholders.
        // (لو مسحنا القيم قبل التعديل، العمود NOT NULL هيرفض القيمة الفاضية.)
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `categories` MODIFY `image` VARCHAR(255) NULL DEFAULT NULL');
            DB::statement('ALTER TABLE `categories` MODIFY `sub_image` VARCHAR(255) NULL DEFAULT NULL');
        }

        // تنضيف القيم القديمة اللي هي في الحقيقة placeholder مش صورة حقيقية.
        DB::table('categories')
            ->whereIn('sub_image', $this->placeholderUrls)
            ->update(['sub_image' => null]);

        DB::table('categories')
            ->whereIn('image', $this->placeholderUrls)
            ->update(['image' => null]);
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `categories` MODIFY `image` VARCHAR(255) NOT NULL DEFAULT 'https://i.ibb.co/rffyqbmk/1.png'");
            DB::statement("ALTER TABLE `categories` MODIFY `sub_image` VARCHAR(255) NOT NULL DEFAULT 'https://i.ibb.co/20wDxXg9/3.png'");
        }
    }
};
