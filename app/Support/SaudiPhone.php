<?php

namespace App\Support;

final class SaudiPhone
{
    public static function normalize(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $phone = strtr(trim($phone), [
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);
        $digits = preg_replace('/\D+/', '', $phone);

        if (str_starts_with($digits, '00966')) {
            $digits = substr($digits, 5);
        } elseif (str_starts_with($digits, '966')) {
            $digits = substr($digits, 3);
        }

        if (strlen($digits) === 9 && str_starts_with($digits, '5')) {
            $digits = '0'.$digits;
        }

        return preg_match('/^05[03456789][0-9]{7}$/', $digits) ? $digits : null;
    }

    public static function variants(string $phone): array
    {
        $local = self::normalize($phone);

        if ($local === null) {
            return [];
        }

        $withoutZero = substr($local, 1);

        return [$local, $withoutZero, '966'.$withoutZero, '+966'.$withoutZero, '00966'.$withoutZero];
    }
}
