<?php 
namespace MoneyProblem\Domain;

class Money{
    private float $amount;
    private Currency $currency;

    public function __construct(float $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @throws CanNotAddDifferentCurrency
     */
    public function add(Money $money): Money {
        if ($money->currency != $this->currency) {
            throw new CanNotAddDifferentCurrency($money->currency, $this->currency);
        }
        return new Money($this->amount + $money->amount, $this->currency);
    }

    public function times(int $value): Money {
        if ($value < 0) {
            throw new CanNotMultiplyByNegativeValue($value);
        }
        return new Money($this->amount * $value, $this->currency);
    }

    public function divide(int $value): Money {
        if ($value == 0) {
            throw new CanNotDivideByZero($value);
        }
        return new Money($this->amount / $value, $this->currency);
    }
}