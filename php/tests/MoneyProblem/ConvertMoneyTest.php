<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Bank;
use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\Money;
use MoneyProblem\Domain\MissingExchangeRateException;
use PHPUnit\Framework\TestCase;

class ConvertMoneyTest extends TestCase
{
    private Bank $bank;
    protected function setUp(): void
    {
        parent::setUp();
        $this->bank =  Bank::create(Currency::EUR(), Currency::USD(), 1.2);
    }

    /**
     * @throws MissingExchangeRateException
     */
    public function test_bank_convert_eur_to_usd()
    {
        $converted_money = $this->bank->convertMoney(Money::fabricMoney(10, Currency::EUR()), Currency::USD());
        $this->assertEquals(Money::fabricMoney(12, Currency::USD()), $converted_money);
    }

    public function test_bank_convert_eur_to_eur()
    {
        $bank = BankBuilder::create()
            ->withPivotCurrency(Currency::EUR())
            ->withExchangeRate( Currency::USD(), 1.2)
            ->build();
        $converted_money = $bank->convertMoney(Money::fabricMoney(10, Currency::EUR()), Currency::EUR());

        $this->assertEquals(Money::fabricMoney(10, Currency::EUR()), $converted_money);

    }

    public function test_bank_convert_without_exchange_rate()
    {
        $this->expectException(MissingExchangeRateException::class);
        $this->expectExceptionMessage('EUR->KRW');
        $this->bank->convertMoney(Money::fabricMoney(10, Currency::EUR()), Currency::KRW());
    }

    /**
     * @throws MissingExchangeRateException
     */
    public function test_bank_convert_with_different_exchange_rates()
    {
        $money = $this->bank->convertMoney(Money::fabricMoney(10, Currency::EUR()), Currency::USD());
        $this->assertEquals(Money::fabricMoney(12, Currency::USD()), $money);

        $this->bank->addEchangeRate(Currency::EUR(), Currency::USD(), 1.3);

        $money = $this->bank->convertMoney(Money::fabricMoney(10, Currency::EUR()), Currency::USD());
        $this->assertEquals(Money::fabricMoney(13, Currency::USD()), $money);
    }
}
