<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase47\V1;

use Neighborhoods\Kojo\Api;

class Worker implements WorkerInterface
{
    use Worker\Delegate\Repository\AwareTrait;
    use Api\V1\Worker\Service\AwareTrait;
    public const JOB_TYPE_CODE = 'complex_logging_structure';
    public const MAX_ITERATIONS = 5;

    public function work() : WorkerInterface
    {
        $this->fireEvent('new_worker');

        // Schedule another kōjō job of the same type.
        $this->_scheduleNextJob();

        // Delegate the work for the first message.
        $this->_delegateWork();

        // Delegate the work until the observed Queue is empty.
        $iterationCount = 1;
        while ($iterationCount < self::MAX_ITERATIONS) {
            $this->_delegateWork();
            $iterationCount++;
        }



        // Tell Kōjō that we are done and all is well.
        $this->fireEvent('complete_success');
        $this->getApiV1WorkerService()->requestCompleteSuccess()->applyRequest();

        // Fluent interfaces for the love of Pete.
        return $this;
    }

    protected function _delegateWork() : WorkerInterface
    {
        $workerDelegate = $this->getV1WorkerDelegateRepository()->getV1NewWorkerDelegate();
        $workerDelegate->setApiV1WorkerService($this->getApiV1WorkerService());

        $this->fireEvent('working');
        if (extension_loaded('newrelic')) {
            newrelic_end_transaction();
            newrelic_start_transaction(ini_get("newrelic.appname")); // start recording a new transaction
            newrelic_name_transaction(self::JOB_TYPE_CODE);
        }

        $workerDelegate->businessLogic();

        return $this;
    }

    protected function _scheduleNextJob() : WorkerInterface
    {
        $this->fireEvent('schedule_next_job');
        $newJobScheduler = $this->getApiV1WorkerService()->getNewJobScheduler();
        $newJobScheduler->setJobTypeCode(self::JOB_TYPE_CODE)
            ->setWorkAtDateTime(new \DateTime('now'))
            ->save();

        return $this;
    }

    protected function fireEvent(string $event) : WorkerInterface
    {
        $context = ['job_type' => self::JOB_TYPE_CODE, 'event_type' => $event];

        if (extension_loaded('newrelic')) {
            if (extension_loaded('newrelic')) {
                newrelic_record_custom_event($event, $context);
                newrelic_record_custom_event($event, ['job_type' => self::JOB_TYPE_CODE]);
            }
        }

        $this->getApiV1WorkerService()->getLogger()->notice($event, $context);
        return $this;
    }
}
