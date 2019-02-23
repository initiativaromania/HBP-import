<?php

declare(strict_types=1);

use Leaveyou\Console\Input;
use Leaveyou\Console\Parameter;
use Leaveyou\Console\ParameterSet;
use Leaveyou\Console\Tools\CommandLineParser;
use Leaveyou\Console\Tools\Help;
use Leaveyou\Console\Tools\ResultNormalizer;

define('ROOD_DIR', __DIR__);

require_once "vendor/autoload.php";

$parameters = new ParameterSet();
$resultNormalizer = new ResultNormalizer();
$parser = new CommandLineParser($resultNormalizer);
$help = new Help();
$input = new Input($parameters, $parser, $help);





$fileNameParameter = new Parameter('file');
$fileNameParameter->setShortName("f");
$fileNameParameter->setDescription("The file name for the csv file containing the data");
$fileNameParameter->setValueType(Parameter::VALUE_STRING);
$fileNameParameter->setType(Parameter::TYPE_MANDATORY);

$parameters->addParameter($fileNameParameter);




$fileName = $input->getValue('file');


var_dump($fileName);


