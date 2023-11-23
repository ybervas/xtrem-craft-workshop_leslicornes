<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Bank;
use MoneyProblem\Domain\Currency;

class BankBuilder
{

    private Currency $pivotCurrency;
    /**
     * @var mixed
     */
    private $exchangeRates = [];

    public static function create() : BankBuilder
    {
        return new BankBuilder();
    }

    public function withPivotCurrency(Currency $pivotCurrency) : BankBuilder
    {
        $this->pivotCurrency = $pivotCurrency;
        return $this;
    }

    public function withExchangeRate(Currency $currency, float $rate) : BankBuilder
    {
        $this->exchangeRates[(string) $currency] = $rate;
        return $this;
    }

    public function build() : Bank
    {
        $endCurrency = array_keys($this->exchangeRates)[0];
        $bank = Bank::create($this->pivotCurrency, Currency::from($endCurrency), $this->exchangeRates[$endCurrency]);
        foreach ($this->exchangeRates as $currency => $rate) {
            $bank->addEchangeRate($this->pivotCurrency, Currency::from($currency), $rate);
            $bank->addEchangeRate(Currency::from($currency), $this->pivotCurrency, 1 / $rate);
        }
        return $bank;
    }
}