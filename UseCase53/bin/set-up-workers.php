<?php
declare(strict_types=1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Finder\Finder;
use Neighborhoods\Kojo\Api\V1\Job;
use Neighborhoods\KojoFitnessUseCase53;

$discoverableDirectories[] = __DIR__ . '/../src/V1/Environment';
$finder = new Finder();
$finder->name('*.yml');
$finder->files()->in($discoverableDirectories);
$jobTypeService = (new Job\Type\Service())->addYmlServiceFinder($finder);

$worker = $jobTypeService->getNewJobTypeRegistrar();
$worker
    ->setCode('worker')
    ->setWorkerClassUri(KojoFitnessUseCase53\V1\Worker::class)
    ->setWorkerMethod('work')
    ->setName('Worker')
    ->setCanWorkInParallel(false)
    ->setCronExpression('*/10 * * * *')
    ->setDefaultImportance(10)
    ->setScheduleLimit(1)
    ->setScheduleLimitAllowance(1)
    ->setIsEnabled(true)
    ->setAutoCompleteSuccess(false)
    ->setAutoDeleteIntervalDuration('PT0S');
$worker->save();
