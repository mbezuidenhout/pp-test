<?php

namespace Tests\Unit\Utils;

use App\Utils\SouthAfricanIDUtils;
use PHPUnit\Framework\TestCase;

class SouthAfricanIDUtilsTest extends TestCase
{
    public function test_luhn_digit_is_calculated_correctly(): void
    {
        // Example: ID without check digit = '800101500908'
        // Full ID with correct check digit should be '8001015009087'
        $this->assertEquals(7, SouthAfricanIDUtils::calculateLuhnDigit('800101500908'));
    }

    public function test_throws_exception_for_non_numeric_input()
    {
        $this->expectException(\InvalidArgumentException::class);
        SouthAfricanIDUtils::calculateLuhnDigit('abcdefgh1234');
    }

    public function test_throws_exception_for_input_too_long()
    {
        $this->expectException(\InvalidArgumentException::class);
        SouthAfricanIDUtils::calculateLuhnDigit('1234567890123'); // 13 digits
    }

    public function test_throws_exception_for_input_too_short()
    {
        $this->expectException(\InvalidArgumentException::class);
        SouthAfricanIDUtils::calculateLuhnDigit('12345678901'); // 11 digits
    }
}
