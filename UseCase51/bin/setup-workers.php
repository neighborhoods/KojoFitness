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
$jobTypeService = (new Job\Type\Service())->addYmlServiceFinder($finder);

$producerJobCreator = $jobTypeService->getNewJobTypeRegistrar();
$producerJobCreator
    ->setCode('kojofitness_jsclp_logging_robustness_tester_producer')
    ->setWorkerClassUri(\Neighborhoods\KojoFitnessUseCase51\V1\Producer\Facade::class)
    ->setWorkerMethod('start')
    ->setName('KojoFitness JSCLP Logging Robustness Tester Producer')
    // opting to manually schedule this since we only want to see one iteration
    // ->setCronExpression('* * * * *')
    ->setCanWorkInParallel(false)
    ->setDefaultImportance(10)
    ->setScheduleLimit(1)
    ->setScheduleLimitAllowance(1)
    ->setIsEnabled(true)
    ->setAutoCompleteSuccess(false)
    ->setAutoDeleteIntervalDuration('PT0S');
$producerJobCreator->save();

$consumerJobCreator = $jobTypeService->getNewJobTypeRegistrar();
$consumerJobCreator
    ->setCode('kojofitness_jsclp_logging_robustness_tester_consumer')
    ->setWorkerClassUri(\Neighborhoods\KojoFitnessUseCase51\V1\Consumer\Facade::class)
    ->setWorkerMethod('start')
    ->setName('KojoFitness JSCLP Logging Robustness Tester Consumer')
    ->setCanWorkInParallel(true)
    ->setDefaultImportance(10)
    ->setScheduleLimit(0)
    ->setScheduleLimitAllowance(1)
    ->setIsEnabled(true)
    ->setAutoCompleteSuccess(false)
    ->setAutoDeleteIntervalDuration('PT0S');
$consumerJobCreator->save();
