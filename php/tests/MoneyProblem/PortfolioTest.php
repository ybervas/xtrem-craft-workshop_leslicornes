<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\Bank;
use MoneyProblem\Domain\Portfolio;
use PHPUnit\Framework\TestCase;

class PortfolioTest extends TestCase
{

    public function test_empty_portfolio_is_evaluated_to_zero()
    {
        
        $bank = Bank::create(Currency::EUR(), Currency::USD(), 1.2);
        $portfolio = new Portfolio();
        $amout = $portfolio->evaluate(Currency::EUR(), $bank);
        $this->assertEquals(0, $amout);
    }

    public function test_portfolio_is_evaluated_to_the_same_currency()
    {
        $bank = Bank::create(Currency::EUR(), Currency::USD(), 1.2);
        $portfolio = new Portfolio();
        $portfolio->add(10, Currency::EUR());
        $amout = $portfolio->evaluate(Currency::EUR(), $bank);
        $this->assertEquals(10, $amout);
    }

    public function test_portfolio_is_evaluate_usd_to_eur()
    {
        $bank = Bank::create(Currency::USD(), Currency::EUR(), 1.2);
        $portfolio = new Portfolio();
        $portfolio->add(10, Currency::USD());
        $amout = $portfolio->evaluate(Currency::EUR(), $bank);
        $this->assertEquals(12, $amout);
    }

    public function test_portfolio_add_usd_and_krw_and_eur_evaluate_to_eur(){
        $bank = Bank::create(Currency::USD(), Currency::EUR(), 1.2);
        $bank->addEchangeRate(Currency::KRW(), Currency::EUR(), 1.5);
        $bank->addEchangeRate(Currency::EUR(), Currency::EUR(), 1);
        $portfolio = new Portfolio();
        $portfolio->add(10, Currency::USD());
        $portfolio->add(10, Currency::KRW());
        $portfolio->add(10, Currency::EUR());

        $amout = $portfolio->evaluate(Currency::EUR(), $bank);
        $this->assertEquals(37, $amout);
    }
} 