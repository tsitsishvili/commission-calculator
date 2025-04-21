<?php

namespace Tests\Unit\Service;


use App\Enums\Currency;
use App\Provider\StaticExchangeRateProvider;
use App\Service\ExchangeService;
use PHPUnit\Framework\TestCase;

class ExchangeServiceTest extends TestCase
{
    private ExchangeService $exchangeService;
    private array $rates = [
        'EUR' => 1,
        'USD' => 1.15,
        'JPY' => 100,
    ];

    protected function setUp(): void
    {
        $this->exchangeService =  new ExchangeService(
            new StaticExchangeRateProvider($this->rates)
        );
    }

    public function testConvertCurrency(): void
    {
        // Test EUR to USD
        $this->assertEquals(
            100 / $this->rates['USD'],
            $this->exchangeService->convertToBase(100.0, Currency::USD)
        );
        
        // Test EUR to JPY
        $this->assertEquals(
            100 / $this->rates['JPY'],
            $this->exchangeService->convertToBase(100.0, Currency::JPY)
        );

        // Test USD to EUR
        $this->assertEquals(
            100 * $this->rates['USD'],
            $this->exchangeService->convertFromBase(100.0, Currency::USD)
        );

        // Test JPY to EUR
        $this->assertEquals(
            100 * $this->rates['JPY'],
            $this->exchangeService->convertFromBase(100.0, Currency::JPY)
        );

        // Test EUR to EUR
        $this->assertEquals(
            100.0,
            $this->exchangeService->convertFromBase(100.0, Currency::EUR)
        );
    }
    
    public function testNoConversionForSameCurrency(): void
    {
        $result = $this->exchangeService->convertFromBase(100.0, Currency::EUR);
        // No conversion should be applied for the same currency
        $this->assertEquals(100.0, $result);
    }
}