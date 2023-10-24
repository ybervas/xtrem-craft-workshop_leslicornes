<?php

namespace MoneyProblem\Domain;

class CanNotDivideByZero extends \Exception
{

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        parent::__construct(sprintf('dont divide by %s', $value));

    }
}