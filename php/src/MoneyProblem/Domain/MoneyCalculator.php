<?php

namespace MoneyProblem\Domain;
use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\Money;

class MoneyCalculator
{
    /**
     * @param float $initialAmount
     * @param Currency $currency
     * @param float $addedAmount
     */
    public static function add(float $initialAmount, Currency $currency, float $addedAmount): float
    {
        return $initialAmount + $addedAmount;
    }

    /**
     * @param float $initialAmount
     * @param Currency $currency
     * @param int $value
     */
    public static function times(float $initialAmount, Currency $currency, int $value): float
    {
        return $initialAmount * $value;
    }
    
    /**
     * @param float $initialAmount
     * @param Currency $currency
     * @param int $value
     */
    public static function divide(float $initialAmount, Currency $currency, int $value): float
    {
        return $initialAmount / $value;
    }
}