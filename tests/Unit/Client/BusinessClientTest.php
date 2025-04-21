<?php

namespace Tests\Unit\Client;

use App\Client\BusinessClient;
use App\Client\PrivateClient;
use App\Enums\Currency;
use App\Enums\OperationType;
use App\Model\Operation;
use App\Provider\StaticExchangeRateProvider;
use App\Service\ExchangeService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BusinessClientTest extends TestCase
{
    private BusinessClient $client;

    protected function setUp(): void
    {
        $this->client = new BusinessClient();
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
            userType: 'business',
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
            'withdraw commission' => [
                'date' => '2023-10-01',
                'userId' => 1,
                'amount' => 1000.00,
                'currency' => Currency::EUR->value,
                'expectedFee' => 1000.00 * 0.005,
            ],
            'withdraw commission with limit' => [
                'date' => '2023-10-01',
                'userId' => 1,
                'amount' => 100000.00,
                'currency' => Currency::EUR->value,
                'expectedFee' => 100000.00 * 0.005,
            ],
        ];
    }
}