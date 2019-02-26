<?php

namespace Hbp\Import;

use Exception;

class IncorrectStrategyException extends Exception
{

    /**
     * IncorrectStrategyException constructor.
     * @param string $getMessage
     */
    public function __construct(string $getMessage)
    {
        parent::__construct("Strategy validation issue: $getMessage");
    }
}
