<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Bank;
use MoneyProblem\Domain\Currency;

class BankBuilder
{

    private Currency $pivotCurrency;

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
        return Bank::create($this->pivotCurrency, $endCurrency, $this->exchangeRates[$endCurrency]);
    }
}