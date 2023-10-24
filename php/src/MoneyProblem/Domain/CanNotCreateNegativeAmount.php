<?php

namespace MoneyProblem\Domain;

class CanNotCreateNegativeAmount extends \Exception
{

    public function __construct()
    {
        parent::__construct(sprintf('dont create negative amount'));

    }
}