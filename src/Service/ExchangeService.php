<?php

declare(strict_types=1);

namespace App\Service;

use App\Enums\Currency;
use App\Provider\ApiCurrencyExchangeRateProvider;
use App\Provider\ExchangeRateProviderInterface;

class ExchangeService
{
    private array $rates = [];
    private ExchangeRateProviderInterface $rateProvider;

    /**
     * @throws \Exception
     */
    public function __construct(
        ?ExchangeRateProviderInterface $rateProvider = null,
    ) {
        $this->rateProvider = $rateProvider ?? new ApiCurrencyExchangeRateProvider();

        $this->loadRates();
    }

    public function convertToBase(float $amount, Currency $currency): float
    {
        return $amount / $this->rates[$currency->value];
    }

    public function convertFromBase(float $amount, Currency $currency): float
    {
        return $amount * $this->rates[$currency->value];
    }

    /**
     * @throws \Exception
     */
    private function loadRates(): void
    {
        $this->rates = $this->rateProvider->getRates();
    }
}
