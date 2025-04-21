<?php

namespace Tests\Unit\Client;

use App\Client\PrivateClient;
use App\Enums\Currency;
use App\Enums\OperationType;
use App\Model\Operation;
use App\Provider\StaticExchangeRateProvider;
use App\Service\ExchangeService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PrivateClientTest extends TestCase
{
    private PrivateClient $client;
    
    protected function setUp(): void
    {
        $exchangeService = new ExchangeService(
            new StaticExchangeRateProvider(
                [
                    'EUR' => 1,
                    'USD' => 1.15,
                    'JPY' => 100,
                ]
            )
        );

        $this->client = new PrivateClient(
            $exchangeService
        );
    }

    #[DataProvider('withdrawDataProvider')]
    public function testCalculateWithdrawCommission(
        string $date,
        int $userId,
        float $amount,
        string $currency,
        float $expectedFee
    ): void
    {
        $operation = new Operation(
            date: $date,
            userId: $userId,
            userType: 'private',
            operationType: 'withdraw',
            amount: $amount,
            currency: $currency
        );
        
        $result = $this->client->calculateWithdrawFee($operation);

        $this->assertEquals($expectedFee, $result);
    }

    public static function withdrawDataProvider(): array
    {
        return [
            'withdraw commission, first transaction is free' => [
                'date' => '2023-10-01',
                'userId' => 1,
                'amount' => 1000.00,
                'currency' => Currency::EUR->value,
                'expectedFee' => 0.00,
            ],
            'withdraw commission with fee, exceeds weekly free transaction limit' => [
                'date' => '2023-10-01',
                'userId' => 1,
                'amount' => 2000.00,
                'currency' => Currency::EUR->value,
                'expectedFee' => 3.00,
            ],
            'withdraw commission free for next week' => [
                'date' => '2023-10-20',
                'userId' => 1,
                'amount' => 1000.00,
                'currency' => Currency::EUR->value,
                'expectedFee' => 0.00,
            ],
            'withdraw commission free with fee in JPY' => [
                'date' => '2023-10-01',
                'userId' => 2,
                'amount' => 100000,
                'currency' => Currency::JPY->value,
                'expectedFee' => 0.00,
            ],
            'withdraw commission with fee in JPY' => [
                'date' => '2023-10-20',
                'userId' => 2,
                'amount' => 20000000,
                'currency' => Currency::JPY->value,
                'expectedFee' => (20000000 / 100 - 1000) * 0.003 * 100,
            ],
        ];
    }
}