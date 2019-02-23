<?php

declare(strict_types=1);

use Leaveyou\Console\Input;
use Leaveyou\Console\Parameter;
use Leaveyou\Console\ParameterSet;
use Leaveyou\Console\Tools\CommandLineParser;
use Leaveyou\Console\Tools\Help;
use Leaveyou\Console\Tools\ResultNormalizer;

$fileNameParameter = new Parameter('file');
$fileNameParameter->setShortName("f")
    ->setDescription("The file name for the csv file containing the data")
    ->setValueType(Parameter::VALUE_STRING)
    ->setType(Parameter::TYPE_MANDATORY);

$importStrategyParameter = new Parameter("strategy");
$importStrategyParameter->setShortName('s')
    ->setDescription("the name of the import strategy")
    ->setValueType(Parameter::VALUE_STRING)
    ->setType(Parameter::TYPE_MANDATORY);

$parameters = new ParameterSet();
$parameters->addParameter($fileNameParameter);
$parameters->addParameter($importStrategyParameter);

$resultNormalizer = new ResultNormalizer();
$parser = new CommandLineParser($resultNormalizer);
$help = new Help();
$input = new Input($parameters, $parser, $help);

return $input;