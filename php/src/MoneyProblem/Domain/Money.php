<?php

namespace MoneyProblem\Domain;

use phpDocumentor\Reflection\Types\Boolean;

class Money
{
    private float $amount;
    private Currency $currency;

    /**
     * @throws CanNotCreateNegativeAmount
     */
    static function fabricMoney(float $amount, Currency $currency): Money
    {
        if ($amount < 0) {
            throw new CanNotCreateNegativeAmount();
        }
        return new Money($amount, $currency);
    }
    public function __construct(float $amount, Currency $currency)
    {

        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @throws CanNotAddDifferentCurrency
     */
    public function add(Money $money): Money
    {
        if ($money->currency != $this->currency) {
            throw new CanNotAddDifferentCurrency($money->currency, $this->currency);
        }
        return Money::fabricMoney($this->amount + $money->amount, $this->currency);
    }

    public function times(int $value): Money
    {
        return Money::fabricMoney($this->amount * $value, $this->currency);
    }

    public function divide(int $value): Money
    {
        if ($value == 0) {
            throw new CanNotDivideByZero($value);
        }
        return Money::fabricMoney($this->amount / $value, $this->currency);
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getAmmount(): float
    {
        return $this->amount;
    }

    public function hasCurrency(Currency $currency): bool
    {
        return $this->currency->equals($currency);
    }

    public function convert(int $amount, Currency $currency): Money
    {
        return new Money($amount, $currency);
    }
}
