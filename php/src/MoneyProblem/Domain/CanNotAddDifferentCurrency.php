<?php

namespace MoneyProblem\Domain;

class CanNotAddDifferentCurrency extends \Exception
{

    /**
     * @param Currency $startCurrency
     * @param Currency $endCurrency
     */
    public function __construct(Currency $startCurrency, Currency $endCurrency)
    {
        parent::__construct(sprintf('%s+%s', $startCurrency, $endCurrency));

    }
}