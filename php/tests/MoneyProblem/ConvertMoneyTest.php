<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Bank;
use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\MissingExchangeRateException;
use PHPUnit\Framework\TestCase;

class ConvertMoneyTest extends TestCase
{
    private $bank;
    protected function setUp(): void {
        parent::setUp();
        $this->bank =  Bank::create(Currency::EUR(), Currency::USD(), 1.2);
    }
    
    public function test_bank_convert_eur_to_usd()
    {
        $converted_money = $this->bank->convert(10, Currency::EUR(), Currency::USD());
        $this->assertEquals(12, $converted_money);
    }

    public function test_bank_convert_eur_to_eur()
    {
        $converted_money = $this->bank->convert(10, Currency::EUR(), Currency::EUR());
        $this->assertEquals(10, $converted_money);
    }

    public function test_bank_convert_without_exchange_rate()
    {
        $this->expectException(MissingExchangeRateException::class);
        $this->expectExceptionMessage('EUR->KRW');
        $this->bank->convert(10, Currency::EUR(), Currency::KRW());
    }

    public function test_bank_convert_with_different_exchange_rates()
    {
        $this->assertEquals(12, $this->bank->convert(10, Currency::EUR(), Currency::USD()));
        $this->bank->addEchangeRate(Currency::EUR(), Currency::USD(), 1.3);
        $this->assertEquals(13, $this->bank->convert(10, Currency::EUR(), Currency::USD()));
    }

}