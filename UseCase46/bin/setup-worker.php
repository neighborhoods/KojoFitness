<?php
declare(strict_types=1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Finder\Finder;
use Neighborhoods\Kojo\Api\V1\Job;

$discoverableDirectories[] = __DIR__ . '/../src/V1/Environment';
$finder = new Finder();
$finder->name('*.yml');
$finder->files()->in($discoverableDirectories);
$jobCreator = (new Job\Type\Service())->addYmlServiceFinder($finder)->getNewJobTypeRegistrar();
$jobCreator->setCode('complex_logging_structure')
    ->setWorkerClassUri(\Neighborhoods\KojoFitnessUseCase46\V1\Worker\Facade::class)
    ->setWorkerMethod('start')
    ->setName('Protean DLCP Example')
    ->setCronExpression('* * * * *')
    ->setCanWorkInParallel(true)
    ->setDefaultImportance(10)
    ->setScheduleLimit(10)
    ->setScheduleLimitAllowance(1)
    ->setIsEnabled(true)
    ->setAutoCompleteSuccess(false)
    ->setAutoDeleteIntervalDuration('PT60S');
$jobCreator->save();

$jobCreator = (new Job\Type\Service())->addYmlServiceFinder($finder)->getNewJobTypeRegistrar();
$jobCreator->setCode('same_job_different_code')
    ->setWorkerClassUri(\Neighborhoods\KojoFitnessUseCase46\V1\Worker\Facade::class)
    ->setWorkerMethod('start')
    ->setName('Protean DLCP Example')
    ->setCronExpression('* * * * *')
    ->setCanWorkInParallel(true)
    ->setDefaultImportance(10)
    ->setScheduleLimit(10)
    ->setScheduleLimitAllowance(1)
    ->setIsEnabled(true)
    ->setAutoCompleteSuccess(false)
    ->setAutoDeleteIntervalDuration('PT60S');
$jobCreator->save();
