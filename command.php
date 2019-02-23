<?php

declare(strict_types=1);

use Hbp\Import\Application;
use Hbp\Import\StrategyCollection;

define('ROOT_DIR', __DIR__);

if (!is_file(ROOT_DIR . "/vendor/autoload.php")) {
    die("Run composer install first");
}

require_once ROOT_DIR . "/vendor/autoload.php";

$input = require_once ROOT_DIR . "/bootstrap/input.php";

$strategies = new StrategyCollection();

$application = new Application($strategies);


$fileName = $input->getValue('file');
$fileName = $input->getValue('strategy');


try {
    $application->run();
} catch (Throwable $e) {
    echo $e->getMessage();
    die("\n");
}
