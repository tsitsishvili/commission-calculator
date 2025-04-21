<?php

declare(strict_types=1);

namespace App\Model;

use App\Enums\Currency;
use App\Enums\OperationType;
use App\Enums\UserType;

class Operation
{
    public \DateTime $date;
    public int $userId;
    public UserType $userType;
    public OperationType $operationType;
    public float $amount;
    public Currency $currency;

    /**
     * @throws \DateException
     */
    public function __construct(
        string $date,
        int $userId,
        string $userType,
        string $operationType,
        float $amount,
        string $currency,
    ) {
        $this->date = new \DateTime($date);
        $this->userId = $userId;
        $this->userType = UserType::from($userType);
        $this->operationType = OperationType::from($operationType);
        $this->amount = $amount;
        $this->currency = Currency::from($currency);
    }
}
