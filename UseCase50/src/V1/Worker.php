<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase50\V1;

use Neighborhoods\Kojo\Api;

class Worker implements WorkerInterface
{
    use Api\V1\Worker\Service\AwareTrait;

    public function work() : WorkerInterface
    {
        $workerService = $this->getApiV1WorkerService();

        if ($workerService->getTimesCrashed() > 0) {
            $workerService->getLogger()->warning(
                'This should not be occurring regularly',
                [
                    'job_type_code' => Worker\Facade::JOB_TYPE_CODE,
                    'event_type' => 'execution_environment_crash',
                    'job_id' => $workerService->getJobId()
                ]
            );
        }

        $this->scheduleClone();

        $this->businessLogic();

        return $this;
    }

    private function scheduleClone() : WorkerInterface
    {
        $newJobScheduler = $this->getApiV1WorkerService()->getNewJobScheduler();
        $newJobScheduler->setJobTypeCode(Worker\Facade::JOB_TYPE_CODE)
            ->setWorkAtDateTime(new \DateTime())
            ->save();

        return $this;
    }

    private function businessLogic() : WorkerInterface
    {
        while (true) {
            if (random_int(0, 1) === 0) {
                $this->succeed();
            } else {
                $this->fail();
            }
        }

        // unreachable
        $this->getApiV1WorkerService()->requestCompleteSuccess()->applyRequest();

        return $this;
    }

    private function succeed() : WorkerInterface
    {
        $this->getApiV1WorkerService()->getLogger()->info(
            'Userspace working',
            [
                'job_type_code' => Worker\Facade::JOB_TYPE_CODE,
                'event_type' => 'userspace_working',
                'job_id' => $this->getApiV1WorkerService()->getJobId()
            ]
        );

        return $this;
    }

    private function fail() : WorkerInterface
    {
        if (random_int(0, 1) === 0) {
            $this->getApiV1WorkerService()->getLogger()->info(
                'Userspace failing with uncaught exception',
                [
                    'job_type_code' => Worker\Facade::JOB_TYPE_CODE,
                    'event_type' => 'userspace_failing_uncaught_exception',
                    'job_id' => $this->getApiV1WorkerService()->getJobId()
                ]
            );
            throw new \Exception('Uncaught userspace exception');
        } else {
            $this->getApiV1WorkerService()->getLogger()->info(
                'Userspace failing with uncaught error',
                [
                    'job_type_code' => Worker\Facade::JOB_TYPE_CODE,
                    'event_type' => 'userspace_failing_uncaught_error',
                    'job_id' => $this->getApiV1WorkerService()->getJobId()
                ]
            );
            throw new \Error('Uncaught userspace error');
        }

        // unreachable
        return $this;
    }
}
