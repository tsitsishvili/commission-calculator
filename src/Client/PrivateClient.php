<?php

declare(strict_types=1);

namespace App\Client;

use App\Model\Operation;
use App\Service\ExchangeService;

class PrivateClient
{
    private array $history = [];
    private ExchangeService $exchangeService;
    private const float WITHDRAW_FREE_AMOUNT_EUR = 1000.00;
    private const int WITHDRAW_FREE_OPS_PER_WEEK = 3;
    private const float WITHDRAW_FEE_PERCENT = 0.003;

    public function __construct(?ExchangeService $exchangeService = null)
    {
        $this->exchangeService = $exchangeService ?: new ExchangeService();
    }

    /**
     * @throws \Exception
     */
    public function calculateWithdrawFee(Operation $operation): float
    {
        $weekKey = $operation->date->format('o-W');
        $userId = $operation->userId;

        // Initialize the history for the user if it doesn't exist
        if (!isset($this->history[$userId][$weekKey])) {
            $this->history[$userId][$weekKey] = [
                'count' => 0,
                'total_eur' => 0.0,
            ];
        }

        // Calculate the amount in EUR for the operation
        $amountEur = $this->exchangeService->convertToBase($operation->amount, $operation->currency);

        // Reference the user's history for the current week
        $week = &$this->history[$userId][$weekKey];

        // If the user did not have already reached the limit of free operations for the week
        if ($week['count'] < self::WITHDRAW_FREE_OPS_PER_WEEK) {
            $remainingFree = max(0, self::WITHDRAW_FREE_AMOUNT_EUR - $week['total_eur']);

            // Fully for free withdrawal
            if ($amountEur <= $remainingFree) {
                $week['total_eur'] += $amountEur;
                ++$week['count'];

                return 0.0;
            }

            // Partially for free withdrawal
            $feeableEur = $amountEur - $remainingFree;
            $feeableOriginalCurrency = $this->exchangeService->convertFromBase($feeableEur, $operation->currency);

            $week['total_eur'] = self::WITHDRAW_FREE_AMOUNT_EUR;
            ++$week['count'];

            return $feeableOriginalCurrency * self::WITHDRAW_FEE_PERCENT;
        }

        // If the user already reached the limit of free operations for the week
        return $operation->amount * self::WITHDRAW_FEE_PERCENT;
    }
}
