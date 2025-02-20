<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\CanNotAddDifferentCurrency;
use MoneyProblem\Domain\CanNotCreateNegativeAmount;
use MoneyProblem\Domain\CanNotDivideByZero;
use MoneyProblem\Domain\CanNotMultiplyByNegativeValue;
use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\MissingExchangeRateException;
use MoneyProblem\Domain\Money;
use MoneyProblem\Domain\MoneyCalculator;
use PHPUnit\Framework\TestCase;

class MoneyCalculatorTest extends TestCase
{
    public function test_add_in_usd()
    {
        $money = MoneyCalculator::add(5, Currency::USD(), 10);

        $this->assertEquals(15, $money);
        $this->assertIsFloat($money);
        $this->assertNotNull($money);
    }

    /**
     * @throws CanNotAddDifferentCurrency
     */
    public function test_can_add_money_when_currency_are_the_same(){
        $cinqUsd = Money::fabricMoney(5, Currency::USD());
        $dixUsd = Money::fabricMoney(10, Currency::USD());

        $money = $cinqUsd->add($dixUsd);

        $this->assertEquals(Money::fabricMoney(15, Currency::USD()), $money);
    }

    public function test_can_not_add_money_when_currency_are_different(){
        $cinqUsd = Money::fabricMoney(5, Currency::USD());
        $dixEur = Money::fabricMoney(10, Currency::EUR());

        $this->expectException(CanNotAddDifferentCurrency::class);
        $this->expectExceptionMessage('EUR+USD');

        $money = $cinqUsd->add($dixEur);
    }

    public function test_multiply_eur(){
        $dixEur = Money::fabricMoney(10, Currency::EUR());
        $money = $dixEur->times(10);

        $this->assertEquals(Money::fabricMoney(100, Currency::EUR()), $money);
    }

    public function test_multiply_negative(){
        $dixEur = Money::fabricMoney(10, Currency::EUR());

        $this->expectException(CanNotCreateNegativeAmount::class);
        $this->expectExceptionMessage('dont create negative amount');

        $money = $dixEur->times(-10);
    }

    /**
     * @throws CanNotDivideByZero
     */
    public function test_divide_by_zero(){
        $dixEur = Money::fabricMoney(10, Currency::EUR());

        $this->expectException(CanNotDivideByZero::class);
        $this->expectExceptionMessage('dont divide by 0');

        $money = $dixEur->divide(0);
    }

    /**
     * @throws CanNotDivideByZero
     */
    public function test_divide_eur(){
        $dixEur = Money::fabricMoney(100, Currency::EUR());
        $money = $dixEur->divide(10);

        $this->assertEquals(Money::fabricMoney(10, Currency::EUR()), $money);
    }

    public function test_multiply_in_euros()
    {
        $money = MoneyCalculator::times(10, Currency::USD(), 2);

        $this->assertEquals(20, $money);
        $this->assertLessThan($money, 0);
    }

    public function test_divide_in_korean_won_returns_float()
    {
        $money = MoneyCalculator::divide(4002, Currency::USD(), 4);
        $this->assertEquals(1000.5, $money);
    }
}
