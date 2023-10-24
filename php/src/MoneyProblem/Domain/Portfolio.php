<?php

namespace MoneyProblem\Domain;


class Portfolio
{

    private $amount;
    public function __construct()
    {
        $this->amount = array();
    }

    public function evaluate(Currency $currency, Bank $bank)
    {
        $result = 0;
        foreach ($this->amount as $data) {
            $result += $bank->convert($data["amount"], $data["currency"], $currency);
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
}