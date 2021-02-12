<?php
declare(strict_types=1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Finder\Finder;
use Neighborhoods\Kojo\Api\V1\Job;
use Neighborhoods\KojoFitnessUseCase52;

$discoverableDirectories[] = __DIR__ . '/../src/V1/Environment';
$finder = new Finder();
$finder->name('*.yml');
$finder->files()->in($discoverableDirectories);
$jobTypeService = (new Job\Type\Service())->addYmlServiceFinder($finder);

$delegatorJobCreator = $jobTypeService->getNewJobTypeRegistrar();
$delegatorJobCreator
    ->setCode('delegator')
    ->setWorkerClassUri(KojoFitnessUseCase52\V1\Delegator::class)
    ->setWorkerMethod('work')
    ->setName('Delegator')
    // don't statically schedule, we want to create a delegator job manually
    // ->setCronExpression('* * * * *')
    ->setCanWorkInParallel(false)
    ->setDefaultImportance(10)
    ->setScheduleLimit(1)
    ->setScheduleLimitAllowance(1)
    ->setIsEnabled(true)
    ->setAutoCompleteSuccess(false)
    ->setAutoDeleteIntervalDuration('PT0S');
$delegatorJobCreator->save();

$delegatedWorkerJobCreator = $jobTypeService->getNewJobTypeRegistrar();
$delegatedWorkerJobCreator
    ->setCode('delegated_worker')
    ->setWorkerClassUri(KojoFitnessUseCase52\V1\DelegatedWorker::class)
    ->setWorkerMethod('work')
    ->setName('Delegated Worker')
    ->setCanWorkInParallel(true)
    // higher than the delegator so we finish the work we have before delegating more
    ->setDefaultImportance(12)
    ->setScheduleLimit(0)
    ->setScheduleLimitAllowance(1)
    ->setIsEnabled(true)
    ->setAutoCompleteSuccess(false)
    ->setAutoDeleteIntervalDuration('PT0S');
$delegatedWorkerJobCreator->save();

$userspaceMutexedWorker = $jobTypeService->getNewJobTypeRegistrar();
$userspaceMutexedWorker
    ->setCode('userspace_mutexed_worker')
    ->setWorkerClassUri(KojoFitnessUseCase52\V1\UserspaceMutexedWorker::class)
    ->setWorkerMethod('work')
    ->setName('Userspace Mutexed Worker')
    ->setCanWorkInParallel(true)
    ->setDefaultImportance(10)
    // this will use up all the process pool slots, just like the delegator/worker
    ->setScheduleLimit(999999)
    ->setScheduleLimitAllowance(1)
    ->setIsEnabled(true)
    ->setAutoCompleteSuccess(false)
    ->setAutoDeleteIntervalDuration('PT0S');
$userspaceMutexedWorker->save();
