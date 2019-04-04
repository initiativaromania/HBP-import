<?php

declare(strict_types=1);

use Hbp\Import\Application;
use Hbp\Import\Database\Database;
use Hbp\Import\Database\Repository\CompanyRepository;
use Hbp\Import\Database\Repository\ContractRepository;
use Hbp\Import\Database\Repository\InstitutionRepository;
use Hbp\Import\Database\Repository\TenderRepository;
use Hbp\Import\ExceptionHandler;
use Hbp\Import\ImportStrategies\ContractCsvV1Strategy;
use Hbp\Import\ImportStrategies\TenderCsvV1Strategy;
use Hbp\Import\ImportStrategies\ContractXlsxV1Strategy;
use Hbp\Import\ImportStrategies\TenderXlsxV1Strategy;
use Hbp\Import\PerformanceLog;
use Hbp\Import\StrategyCollection;

define('START_TIME', microtime(true));
define('ROOT_DIR', __DIR__);

if (!@include ROOT_DIR . "/vendor/autoload.php") {
    die("\e[93mRun composer install first\e[0m" . PHP_EOL);
}

require_once ROOT_DIR . "/config.php";

require_once ROOT_DIR . "/bootstrap/helpers.php";

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

$pdo = new PDO("pgsql:host=$host;dbname=$database;port=$port", $user, $password);

$database = new Database($pdo);
$database->registerRepository('contract', new ContractRepository($pdo));
$database->registerRepository('company', new CompanyRepository($pdo));
$database->registerRepository('institution', new InstitutionRepository($pdo));
$database->registerRepository('tender', new TenderRepository($pdo));

$strategies = new StrategyCollection();

$contractXlsxStrategy = new ContractXlsxV1Strategy($database);
$contractCsvStrategy = new ContractCsvV1Strategy($database);
$tenderCsvStrategy = new TenderCsvV1Strategy($database);
$tenderXlsxStrategy = new TenderXlsxV1Strategy($database);

$strategies->add($contractXlsxStrategy, "ContractXlsxV1Strategy");
$strategies->add($contractCsvStrategy, "ContractCsvV1Strategy");
$strategies->add($tenderCsvStrategy, "TenderCsvV1Strategy");
$strategies->add($tenderXlsxStrategy, "TenderXlsxV1Strategy");

$strategyName = $input->getValue('strategy');
$strategy = $strategies->getStrategy($strategyName);

$application = new Application($input, $database);
$application->run($strategy);
