<?php

declare(strict_types=1);

namespace App\Provider;

use App\Enums\Currency;

interface ExchangeRateProviderInterface
{
    /**
     * Get the exchange rates with EUR as the base currency.
     *
     * @return array<string, float> Array of currency rates with currency code as a key
     *
     * @throws \Exception If unable to retrieve rates
     */
    public function getRates(): array;
}
