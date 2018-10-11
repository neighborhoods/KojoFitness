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
$jobCreator->setCode('namespace_lock_v1')
    ->setWorkerClassUri(\Neighborhoods\KojoFitnessFunction41\V1\Worker\Facade::class)
    ->setWorkerMethod('start')
    ->setName('Multi Server Namespace Lock Test V1')
    ->setCronExpression('* * * * *')
    ->setCanWorkInParallel(true)
    ->setDefaultImportance(10)
    ->setScheduleLimit(50)
    ->setScheduleLimitAllowance(1)
    ->setIsEnabled(true)
    ->setAutoCompleteSuccess(false)
    ->setAutoDeleteIntervalDuration('PT0S');
$jobCreator->save();
