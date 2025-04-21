<?php

declare(strict_types=1);

namespace App\Client;

use App\Model\Operation;

class BusinessClient
{
    private const float WITHDRAW_FEE_PERCENT = 0.005;

    public function calculateWithdrawFee(Operation $operation): float
    {
        return $operation->amount * self::WITHDRAW_FEE_PERCENT;
    }
}
