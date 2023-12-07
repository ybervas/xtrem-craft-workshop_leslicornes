<?php

namespace MoneyProblem\Domain;

use function array_key_exists;

class Bank
{
    private array $exchangeRates = [];
    private array $newExchangeRates = [];
    private Currency $pivotCurrency;

    /**
     * @param array $exchangeRates
     */
    public function __construct(Currency $pivotCurrency, array $exchangeRates = [])
    {
        $this->pivotCurrency = $pivotCurrency;
        $this->exchangeRates = $exchangeRates;
    }

    /**
     * @param Currency $startCurrency
     * @param Currency $endCurrency
     * @param float $rate
     * @return Bank
     */
    public static function create(Currency $startCurrency, Currency $endCurrency, float $rate): Bank
    {
        //GUARD if (non pivot){pete}else bank new
        $bank = new Bank($startCurrency, []);
        $bank->addEchangeRate($startCurrency, $endCurrency, $rate);

        return $bank;
    }

    /**
     * @param Currency $startCurrency
     * @param Currency $endCurrency
     * @param float $rate
     * @return void
     */
    public function addEchangeRate(Currency $startCurrency, Currency $endCurrency, float $rate): void
    {
        if ((string)$startCurrency == (string)$this->pivotCurrency) {
            $this->newExchangeRates[(string)$endCurrency] = $rate;
        }

        $this->exchangeRates[($startCurrency . '->' . $endCurrency)] = $rate;
    }

    public function convertMoney(Money $money, Currency $endCurrency): Money
    {
        if (($this->isconvertible($money->getCurrency(), $endCurrency))) {
            return $money->hasCurrency($endCurrency)
            ? $money
            : $money->convert($money->getAmmount() * $this->exchangeRates[($money->getCurrency() . '->' . $endCurrency)], $endCurrency);
        } 
        else if (($this->isNewConvertible($money->getCurrency(), $endCurrency))) {
            return $money->hasCurrency($endCurrency)
            ? $money
            : $money->convert((int) ($money->getAmmount() / $this->newExchangeRates[(string)$money->getCurrency()]) * $this->newExchangeRates[(string)$endCurrency], $endCurrency);
        }
        else {
            throw new MissingExchangeRateException($money->getCurrency(), $endCurrency);
        }
    }

    /**
     * @param float $amount
     * @param Currency $startCurrency
     * @param Currency $endCurrency
     * @return float
     * @throws MissingExchangeRateException
     */
    public function convert(float $amount, Currency $startCurrency, Currency $endCurrency): float
    {
        $money = new Money($amount, $startCurrency);

        return $this->convertMoney($money, $endCurrency)->getAmmount();
    }

    /**
     * @param Currency $startCurrency
     * @param Currency $endCurrency
     * @return bool
     */
    private function isconvertible(Currency $startCurrency, Currency $endCurrency): bool
    {
        return $startCurrency == $endCurrency || array_key_exists($startCurrency . '->' . $endCurrency, $this->exchangeRates);
    }

    /**
     * @param Currency $startCurrency
     * @param Currency $endCurrency
     * @return bool
     */
    private function isNewConvertible(Currency $startCurrency, Currency $endCurrency): bool
    {
        return $startCurrency == $endCurrency || array_key_exists((string)$startCurrency, $this->newExchangeRates) && array_key_exists((string)$endCurrency, $this->newExchangeRates);
    }
}
