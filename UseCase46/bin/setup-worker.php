<?php
declare(strict_types=1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Neighborhoods\Kojo\Api\V1\Job;
use Symfony\Component\Finder\Finder;

const PDO_CODE_VIOLATES_UNIQUE_CONSTRAINT = 23505;

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
    ->setScheduleLimit(1)
    ->setScheduleLimitAllowance(1)
    ->setIsEnabled(true)
    ->setAutoCompleteSuccess(false)
    ->setAutoDeleteIntervalDuration('PT60S');
attemptSave($jobCreator);

$jobCreator = (new Job\Type\Service())->addYmlServiceFinder($finder)->getNewJobTypeRegistrar();
$jobCreator->setCode('same_job_different_code')
    ->setWorkerClassUri(\Neighborhoods\KojoFitnessUseCase46\V1\Worker\Facade::class)
    ->setWorkerMethod('start')
    ->setName('Protean DLCP Example')
    ->setCronExpression('* * * * *')
    ->setCanWorkInParallel(true)
    ->setDefaultImportance(10)
    ->setScheduleLimit(1)
    ->setScheduleLimitAllowance(1)
    ->setIsEnabled(true)
    ->setAutoCompleteSuccess(false)
    ->setAutoDeleteIntervalDuration('PT60S');
attemptSave($jobCreator);


/**
 * @param Job\Type\RegistrarInterface $jobCreator
 * @throws \Doctrine\DBAL\DBALException
 */
function attemptSave(Job\Type\RegistrarInterface $jobCreator) : void
{
    try {
        $jobCreator->save();
    } catch (\Doctrine\DBAL\DBALException $exception) {
        if ($exception->getPrevious()->getCode() == PDO_CODE_VIOLATES_UNIQUE_CONSTRAINT) {
            echo sprintf(("Ignoring duplicate job exception: %s\n"), $exception->getPrevious()->getMessage());
        } else {
            throw $exception;
        }
    }
}
