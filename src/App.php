<?php

declare(strict_types=1);

namespace App;

use App\Provider\ExchangeRateProviderFactory;
use App\Reader\ReaderFactory;
use App\Service\CommissionCalculator;
use App\Service\ExchangeService;
use Exception;

class App
{
    /**
     * @throws Exception
     */
    public function run(string $source, array $config = []): void
    {
        $reader = ReaderFactory::get($source);

        $operations = $reader->read($source);

        $rateProvider = ExchangeRateProviderFactory::create($config);

        $calculator = new CommissionCalculator(
            exchangeService: new ExchangeService($rateProvider)
        );

        // calculate the commission for each operation
        foreach ($operations as $op) {
            echo $calculator->calculate($op).PHP_EOL;
        }
    }
}
