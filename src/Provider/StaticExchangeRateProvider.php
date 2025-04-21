<?php

declare(strict_types=1);

namespace App\Provider;

class StaticExchangeRateProvider implements ExchangeRateProviderInterface
{
    private array $rates;

    public function __construct(array $rates)
    {
        $this->rates = $rates;
    }

    public function getRates(): array
    {
        return $this->rates;
    }
}
