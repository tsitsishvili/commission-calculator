<?php

declare(strict_types=1);

namespace App\Provider;

use App\Enums\Currency;

class ExchangeRateProviderFactory
{
    public static function create(array $config = []): ExchangeRateProviderInterface
    {
        // Useful for testing purposes
        if (isset($config['static_rates'])) {
            return new StaticExchangeRateProvider($config['static_rates']);
        }

        $baseCurrency = Currency::EUR;

        if (isset($config['base_currency'])) {
            $baseCurrency = Currency::from($config['base_currency']);
        }

        return new ApiCurrencyExchangeRateProvider(
            baseCurrency: $baseCurrency,
        );
    }
}
