<?php

namespace Tests\Unit;

use App\Support\SaudiPhone;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SaudiPhoneTest extends TestCase
{
    #[DataProvider('validPhones')]
    public function test_it_normalizes_saudi_mobile_numbers(string $input, string $expected): void
    {
        $this->assertSame($expected, SaudiPhone::normalize($input));
    }

    public static function validPhones(): array
    {
        return [
            ['0551234567', '0551234567'],
            ['551234567', '0551234567'],
            ['+966 55 123 4567', '0551234567'],
            ['00966-55-123-4567', '0551234567'],
            ['٠٥٥١٢٣٤٥٦٧', '0551234567'],
        ];
    }

    #[DataProvider('invalidPhones')]
    public function test_it_rejects_non_saudi_or_invalid_mobile_numbers(string $input): void
    {
        $this->assertNull(SaudiPhone::normalize($input));
    }

    public static function invalidPhones(): array
    {
        return [
            ['01012345678'],
            ['0511234567'],
            ['055123456'],
            ['not-a-phone'],
        ];
    }
}
