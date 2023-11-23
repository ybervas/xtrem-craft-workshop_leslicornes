<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\Bank;
use MoneyProblem\Domain\MissingExchangeRateException;
use MoneyProblem\Domain\Portfolio;
use PHPUnit\Framework\TestCase;

class PortfolioTest extends TestCase
{

    public function test_empty_portfolio_is_evaluated_to_zero()
    {
        $bank = BankBuilder::create()
            ->withPivotCurrency(Currency::EUR())
            ->withExchangeRate(Currency::USD(), 1.2)
            ->build();
        $portfolio = new Portfolio();
        $amount = $portfolio->evaluate(Currency::EUR(), $bank);
        $this->assertEquals(0, $amount);
    }

    public function test_portfolio_is_evaluated_to_the_same_currency()
    {
        $bank = BankBuilder::create()
            ->withPivotCurrency(Currency::EUR())
            ->withExchangeRate(Currency::USD(), 1.2) // normalement pas besoins mettre valeur par defaut dans le taux de change du builder
            ->build();
        $portfolio = new Portfolio();
        $portfolio->add(10, Currency::EUR());
        $amount = $portfolio->evaluate(Currency::EUR(), $bank);
        $this->assertEquals(10, $amount);
    }

    /**
     * @throws MissingExchangeRateException
     */
    public function test_portfolio_is_evaluate_usd_to_eur()
    {
        $bank = BankBuilder::create()
            ->withPivotCurrency(Currency::EUR())
            ->withExchangeRate(Currency::USD(), 1.2)
            ->build();
        $portfolio = new Portfolio();
        $portfolio->add(10, Currency::USD());
        $amount = $portfolio->evaluate(Currency::EUR(), $bank);
        $this->assertEquals(8, $amount);
    }

    /**
     * @throws MissingExchangeRateException
     */
    public function test_portfolio_add_usd_and_krw_and_eur_evaluate_to_eur(){
        $bank = BankBuilder::create()
            ->withPivotCurrency(Currency::EUR())
            ->withExchangeRate(Currency::USD(), 1/1.2)
            ->withExchangeRate(Currency::KRW(), 1/1.5)
            ->build();
        $portfolio = new Portfolio();
        $portfolio->add(10, Currency::USD());
        $portfolio->add(10, Currency::KRW());
        $portfolio->add(10, Currency::EUR());

        $amount = $portfolio->evaluate(Currency::EUR(), $bank);
        $this->assertEquals(37, $amount);
    }

    public function test_portfolio_add_usd_and_krw_and_eur_evaluate_to_usd(){
        $bank = BankBuilder::create()
            ->withPivotCurrency(Currency::USD())
            ->withExchangeRate(Currency::KRW(), 1.5)
            ->build();
        $portfolio = new Portfolio();
        $portfolio->add(10, Currency::KRW());

        $amount = $portfolio->evaluate(Currency::USD(), $bank);
        $this->assertEquals(40, $amount);
    }
} 