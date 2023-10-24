<?php

namespace MoneyProblem\Domain;

use function array_key_exists;

class Bank
{
    private array $exchangeRates = [];

    /**
     * @param array $exchangeRates
     */
    public function __construct(array $exchangeRates = [])
    {
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
        $bank = new Bank([]);
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
        $this->exchangeRates[($startCurrency . '->' . $endCurrency)] = $rate;
    }

    public function convertMoney(Money $money, Currency $endCurrency): Money
    {
        if (!($this->isconvertible($money->getCurrency(), $endCurrency))) {
            throw new MissingExchangeRateException($money->getCurrency(), $endCurrency);
        }

        return $money->hasCurrency($endCurrency)
            ? $money
            : $money->convert($money->getAmmount() * $this->exchangeRates[($money->getCurrency() . '->' . $endCurrency)], $endCurrency);
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
}
