<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\MoneyCalculator;
use PHPUnit\Framework\TestCase;

class MoneyCalculatorTest extends TestCase
{
    public function test_add_in_usd_returns_value()
    {
        $money = MoneyCalculator::add(5, Currency::USD(), 10);
        $this->assertIsFloat($money);
        $this->assertNotNull($money);
    }

    public function test_multiply_in_euros_returns_positive_number()
    {
        $money = MoneyCalculator::times(10, Currency::USD(), 2);
        $this->assertLessThan($money, 0);
    }

    public function test_divide_in_korean_won_returns_float()
    {
        $money = MoneyCalculator::divide(4002, Currency::USD(), 4);
        $this->assertEquals($money, 1000.5);
    }
}
