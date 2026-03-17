<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'سارة',
                'city' => 'الرياض',
                'rating' => 5,
                'review' => 'المنتجات ممتازة والتوصيل كان سريع جدًا. تجربة شراء سهلة وسلسة.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'محمد',
                'city' => 'جدة',
                'rating' => 5,
                'review' => 'جودة عالية وسعر مناسب. وخدمة العملاء ردّت بسرعة وساعدتني.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'نورة',
                'city' => 'الدمام',
                'rating' => 5,
                'review' => 'التغليف مرتب والطلب وصل بدون أي مشاكل. أكيد هأعيد الطلب مرة ثانية.',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}