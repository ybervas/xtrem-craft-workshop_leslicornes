<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\Bank;
use PHPUnit\Framework\TestCase;

class Portfolio 
{

    private $amount;
    private $bank;

    public function __construct(Bank $bank)
    {
        $this->bank = $bank;
        $this->amount = array();
    }

    public function evaluate(Currency $currency)
    {
        $result = 0;
        foreach ($this->amount as $data) {
            $result += $this->bank->convert($data["amount"], $data["currency"], $currency);
        }
        return $result;
    }

    public function add(int $amount_to_add, Currency $currency)
    {
        if (!isset($this->amount[(string) $currency]["currency"])) {
            $this->amount[(string) $currency] = ["currency"=>$currency, "amount"=>0];
        }
        $this->amount[(string) $currency]["amount"] += $amount_to_add;
    }

    public function getAmount(){
        return $this->amount;
    }
}

class PortfolioTest extends TestCase
{

    public function test_empty_portfolio_is_evaluated_to_zero()
    {
        
        $bank = Bank::create(Currency::EUR(), Currency::USD(), 1.2);
        $portfolio = new Portfolio($bank);
        $amout = $portfolio->evaluate(Currency::EUR(), $bank);
        $this->assertEquals(0, $amout);
    }

    public function test_add_differency_money(){
        $bank = Bank::create(Currency::EUR(), Currency::USD(), 1.2);
        $bank = Bank::create(Currency::EUR(), Currency::KRW(), 1.5);
        $portfolio = new Portfolio($bank);
        $portfolio->add(10, Currency::USD());
        $portfolio->add(10, Currency::EUR());
        $this->assertEquals(2,count($portfolio->getAmount()));
    }

    public function test_portfolio_is_evaluated_to_the_same_currency()
    {
        $bank = Bank::create(Currency::EUR(), Currency::USD(), 1.2);
        $portfolio = new Portfolio($bank);
        $portfolio->add(10, Currency::EUR());
        $amout = $portfolio->evaluate(Currency::EUR(), $bank);
        $this->assertEquals(10, $amout);
    }

    public function test_portfolio_is_evaluate_usd_to_eur()
    {
        $bank = Bank::create(Currency::USD(), Currency::EUR(), 1.2);
        $portfolio = new Portfolio($bank);
        $portfolio->add(10, Currency::USD());
        $amout = $portfolio->evaluate(Currency::EUR(), $bank);
        $this->assertEquals(12, $amout);
    }

    public function test_portfolio_add_usd_and_krw_and_eur_evaluate_to_eur(){
        $bank = Bank::create(Currency::USD(), Currency::EUR(), 1.2);
        $bank->addEchangeRate(Currency::KRW(), Currency::EUR(), 1.5);
        $bank->addEchangeRate(Currency::EUR(), Currency::EUR(), 1);
        $portfolio = new Portfolio($bank);
        $portfolio->add(10, Currency::USD());
        $portfolio->add(10, Currency::KRW());
        $portfolio->add(10, Currency::EUR());

        $amout = $portfolio->evaluate(Currency::EUR());
        $this->assertEquals(37, $amout);
    }
} 