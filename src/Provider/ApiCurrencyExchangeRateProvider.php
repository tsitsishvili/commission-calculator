<?php

declare(strict_types=1);

namespace App\Provider;

use App\Enums\Currency;

class ApiCurrencyExchangeRateProvider implements ExchangeRateProviderInterface
{
    private string $apiKey;
    private string $apiUrl;

    public function __construct(
        private readonly Currency $baseCurrency = Currency::EUR,
    ) {
        $this->apiKey = $_ENV['EXCHANGE_API_KEY'] ?? throw new \InvalidArgumentException('API key is required');
        $this->apiUrl = $_ENV['EXCHANGE_API_URL'] ?? throw new \InvalidArgumentException('API URL is required');
    }

    public function getRates(): array
    {
        $symbols = implode(',', array_map(fn ($c) => $c->value, Currency::cases()));

        $curl = curl_init();

        $params = http_build_query([
            'symbols' => $symbols,
            'base' => $this->baseCurrency->value,
        ]);

        curl_setopt_array($curl, [
            CURLOPT_URL => "$this->apiUrl?$params",
            CURLOPT_HTTPHEADER => [
                'Content-Type: text/plain',
                "apikey: $this->apiKey",
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response, true);

        if (!isset($data['rates'])) {
            throw new \Exception('Unable to fetch exchange rates');
        }

        return $data['rates'];
    }
}
