<?php

declare(strict_types=1);

use Hbp\Import\Application;
use Hbp\Import\ExceptionHandler;
use Hbp\Import\ImportStrategies\TestStrategy;
use Hbp\Import\PerformanceLog;
use Hbp\Import\StrategyCollection;

define('START_TIME', microtime(true));
define('ROOT_DIR', __DIR__);

if (!is_file(ROOT_DIR . "/vendor/autoload.php")) {
    die("Run composer install first");
}

require_once ROOT_DIR . "/vendor/autoload.php";

$input = require_once ROOT_DIR . "/bootstrap/input.php";
$verbosity = $input->getValue('verbose');


set_exception_handler(function ($exception) use ($verbosity) {
    ExceptionHandler::handle($exception, $verbosity);
});

register_shutdown_function(function () use ($verbosity) {
    PerformanceLog::run(START_TIME, $verbosity);
});

$testStrategy = new TestStrategy();

$strategies = new StrategyCollection();
$strategies->add($testStrategy, 'testStrategy');

$application = new Application($input, $strategies);
$application->run();
