<?php 
namespace MoneyProblem\Domain;

class Money{
    private $amount;
    private $currency;

    public function __construct(float $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function add(Money $money): Money {
        if ($money->currency != $this->currency) {
            throw new CanNotAddDifferentCurrency($money->currency, $this->currency);
        }
        return new Money($this->amount + $money->amount, $this->currency);
    }
}