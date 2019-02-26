<?php

declare(strict_types=1);

use Hbp\Import\Application;
use Hbp\Import\ExceptionHandler;
use Hbp\Import\ImportStrategies\TestStrategy;
use Hbp\Import\PerformanceLog;
use Hbp\Import\StrategyCollection;

define('START_TIME', microtime(true));
define('ROOT_DIR', __DIR__);

if (!@include ROOT_DIR . "/vendor/autoload.php") {
    die("\e[93mRun composer install first\e[0m" . PHP_EOL);
}

$input = require_once ROOT_DIR . "/bootstrap/input.php";
$verbosity = $input->getValue('verbose');


set_exception_handler(function ($exception) use ($verbosity) {
    ExceptionHandler::handle($exception, $verbosity);
});

set_error_handler(function ($errorLevel, $errorString, $errorFile, $errorLine) {
    throw new \Hbp\Import\PhpError("[$errorLevel] $errorString in $errorFile on line $errorLine");
}, E_ALL);

register_shutdown_function(function () use ($verbosity) {
    PerformanceLog::run(START_TIME, $verbosity);
});

$testStrategy = new TestStrategy();

$strategies = new StrategyCollection();
$strategies->add($testStrategy, 'testStrategy');

$application = new Application($input, $strategies);
$application->run();
