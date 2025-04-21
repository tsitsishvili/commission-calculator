<?php

namespace Tests\Unit\Helper;

use App\Enums\Currency;
use App\Helper\Rounding;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RoundingTest extends TestCase
{
    #[DataProvider('roundUpProvider')]
    public function testRoundUp(float $amount, Currency $currency, string $expected): void
    {
        $result = Rounding::roundUp($amount, $currency);
        $this->assertEquals($expected, $result);
    }

    public static function roundUpProvider(): array
    {
        return [
            'EUR with exact value' => [10.00, Currency::EUR, '10.00'],
            'EUR with decimal to round up' => [10.125, Currency::EUR, '10.13'],
            'EUR with half cent to round up' => [10.005, Currency::EUR, '10.01'],
            'USD with decimal to round up' => [15.678, Currency::USD, '15.68'],
            'JPY with decimal to round up' => [1000.5, Currency::JPY, '1001'],
            'JPY with exact value' => [1000.0, Currency::JPY, '1000'],
        ];
    }
}