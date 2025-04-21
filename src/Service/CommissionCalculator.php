<?php

declare(strict_types=1);

namespace App\Service;

use App\Client\BusinessClient;
use App\Client\PrivateClient;
use App\Enums\OperationType;
use App\Enums\UserType;
use App\Helper\Rounding;
use App\Model\Operation;

class CommissionCalculator
{
    public const float DEPOSIT_FEE_RATE = 0.0003;

    private PrivateClient $privateClient;
    private BusinessClient $businessClient;

    public function __construct(
        ?ExchangeService $exchangeService = null,
    ) {
        $this->privateClient = new PrivateClient($exchangeService);
        $this->businessClient = new BusinessClient();
    }

    /**
     * @throws \Exception
     */
    public function calculate(Operation $operation): string
    {
        $fee = match ($operation->operationType) {
            OperationType::DEPOSIT => $this->calculateDepositFee($operation),
            OperationType::WITHDRAW => $this->calculateWithdrawFee($operation),
        };

        return Rounding::roundUp($fee, $operation->currency);
    }

    private function calculateDepositFee(Operation $operation): float
    {
        return $operation->amount * self::DEPOSIT_FEE_RATE;
    }

    /**
     * @throws \Exception
     */
    private function calculateWithdrawFee(Operation $operation): float
    {
        return match ($operation->userType) {
            UserType::PRIVATE => $this->privateClient->calculateWithdrawFee($operation),
            UserType::BUSINESS => $this->businessClient->calculateWithdrawFee($operation),
        };
    }
}
