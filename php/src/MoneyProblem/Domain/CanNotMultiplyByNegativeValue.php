<?php

namespace MoneyProblem\Domain;

class CanNotMultiplyByNegativeValue extends \Exception
{

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        parent::__construct(sprintf('dont multiply by %s', $value));

    }
}