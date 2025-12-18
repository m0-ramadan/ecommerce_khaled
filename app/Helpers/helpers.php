<?php

if (!function_exists('get_user_image')) {
    /**
     * إرجاع رابط الصورة الصحيح للمستخدم
     *
     * @param string|null $image
     * @return string
     */
    function get_user_image(?string $image): string
    {
        if (!$image) {
            return config('app.default_user_image', "https://static.vecteezy.com/system/resources/previews/011/209/565/non_2x/user-profile-avatar-free-vector.jpg"); // لا توجد صورة
        }

        // إذا الرابط موجود بالفعل كـ https أو http
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        // الرابط نسبي في قاعدة البيانات
        return asset('storage/' . $image);
    }
}
