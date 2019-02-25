<?php

declare(strict_types=1);

namespace Hbp\Import;

use Throwable;

class ExceptionHandler
{
    static public function handle(Throwable $e, int $verbosity)
    {
        echo "\e[31mError: \e[0m";
        echo $e->getMessage();
        echo PHP_EOL;
        die();
    }
}