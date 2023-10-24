<?php 
namespace MoneyProblem\Domain;

class Money{
    private float $amount;
    private Currency $currency;

    /**
     * @throws CanNotCreateNegativeAmount
     */
    static function fabricMoney(float $amount, Currency $currency): Money{
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
    public function add(Money $money): Money {
        if ($money->currency != $this->currency) {
            throw new CanNotAddDifferentCurrency($money->currency, $this->currency);
        }
        return Money::fabricMoney($this->amount + $money->amount, $this->currency);
    }

    public function times(int $value): Money {
        return Money::fabricMoney($this->amount * $value, $this->currency);
    }

    public function divide(int $value): Money {
        if ($value == 0) {
            throw new CanNotDivideByZero($value);
        }
        return Money::fabricMoney($this->amount / $value, $this->currency);
    }
}