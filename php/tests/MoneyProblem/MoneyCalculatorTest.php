<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\MoneyCalculator;
use PHPUnit\Framework\TestCase;

class MoneyCalculatorTest extends TestCase
{
    public function test_add_in_usd()
    {
        $money = MoneyCalculator::add(5, Currency::USD(), 10);
        $this->assertEquals($money, 15);
        $this->assertIsFloat($money);
        $this->assertNotNull($money);
    }

    public function test_multiply_in_euros()
    {
        $money = MoneyCalculator::times(10, Currency::USD(), 2);
        $this->assertEquals($money, 20);
        $this->assertLessThan($money, 0);
    }

    public function test_divide_in_korean_won_returns_float()
    {
        $money = MoneyCalculator::divide(4002, Currency::USD(), 4);
        $this->assertEquals($money, 1000.5);
    }
}
