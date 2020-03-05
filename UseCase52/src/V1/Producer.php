<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase52\V1;

use Neighborhoods\Kojo\Api;

class Producer implements ProducerInterface
{
    use Api\V1\Worker\Service\AwareTrait;
    use Api\V1\RDBMS\Connection\Service\AwareTrait;

    /** @var \PDO */
    private $pdo;

    public function work() : ProducerInterface
    {
        try {
            $this->happyPath();
            $this->userspacePDOException();
            // PDO with ERRMODE other than ERRMODE_EXCEPTION don't play well with doctrine, so we've added
            // code in commit 4df8cffc98933970608341e2b89d1cb392748978 to guard against it for now
            // I'm leaving in these two cases for the future when we can better handle non-exception errmodes
            // $this->userspacePDOWarnedError();
            // $this->userspacePDOSilentError();
            $this->transactionalJobStateTransition();
        } catch (\Throwable $throwable) {
            $this->getApiV1WorkerService()->getLogger()->error($throwable->getMessage(), ['exception' => $throwable]);
            $this->getApiV1WorkerService()->requestHold()->applyRequest();
        }

        return $this;
    }

    private function happyPath() : ProducerInterface
    {
        $pdo = $this->getPDO();

        try {
            $this->getApiV1RDBMSConnectionService()->usePDO($pdo);

            $pdo->beginTransaction();

            $consumerJobId = $this
                ->getApiV1WorkerService()
                ->getNewJobScheduler()
                ->setJobTypeCode(Consumer\Facade::JOB_TYPE_CODE)
                ->setWorkAtDateTime(new \DateTime())
                ->save()
                ->getJobId();

            $pdo->exec("INSERT INTO userspace_table (consumer_job_id, event_type, is_expected) VALUES ($consumerJobId, 'happy_path', true)");

            $pdo->commit();
        } catch (\Throwable $throwable) {
            $pdo->rollBack();

            throw $throwable;
        }

        return $this;
    }

    private function userspacePDOException() : ProducerInterface
    {
        $pdo = $this->getPDO();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        try {
            $this->getApiV1RDBMSConnectionService()->usePDO($pdo);

            $pdo->beginTransaction();

            $consumerJobId = $this
                ->getApiV1WorkerService()
                ->getNewJobScheduler()
                ->setJobTypeCode(Consumer\Facade::JOB_TYPE_CODE)
                ->setWorkAtDateTime(new \DateTime())
                ->save()
                ->getJobId();

            $pdo->exec("INSERT INTO userspace_table (consumer_job_id, event_type, is_expected) VALUES ($consumerJobId, 'userspace_pdo_exception', false)");

            // violates uniqueness constraint on consumer_job_id
            $pdo->exec("INSERT INTO userspace_table (consumer_job_id, event_type, is_expected) VALUES ($consumerJobId, 'userspace_pdo_exception', false)");

            $pdo->commit();
        } catch (\PDOException $PDOException) {
            $pdo->rollBack();

            // intended behavior
            // the consumer also shouldn't be scheduled, but if it is, it'll emit an error message
            return $this;
        }

        throw new \LogicException('Userspace PDO Exception failed to fail');
    }

    private function userspacePDOWarnedError() : ProducerInterface
    {
        $pdo = $this->getPDO();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
        // warnings suck to use
        error_reporting(E_ALL);
        set_error_handler(function (int $errorNumber, string $errorString, string $errorFile, int $errorLine) {
            throw new \ErrorException($errorString, $errorNumber, $errorNumber, $errorFile, $errorLine);
        });

        try {
            $this->getApiV1RDBMSConnectionService()->usePDO($pdo);

            $pdo->beginTransaction();

            $consumerJobId = $this
                ->getApiV1WorkerService()
                ->getNewJobScheduler()
                ->setJobTypeCode(Consumer\Facade::JOB_TYPE_CODE)
                ->setWorkAtDateTime(new \DateTime())
                ->save()
                ->getJobId();

            $pdo->exec("INSERT INTO userspace_table (consumer_job_id, event_type, is_expected) VALUES ($consumerJobId, 'userspace_pdo_error_warned', false)");

            // violates uniqueness constraint on consumer_job_id
            $pdo->exec("INSERT INTO userspace_table (consumer_job_id, event_type, is_expected) VALUES ($consumerJobId, 'userspace_pdo_error_warned', false)");

            $pdo->commit();
        } catch (\ErrorException $errorException) {
            $pdo->rollBack();

            if (
                $errorException->getSeverity() === E_WARNING &&
                $errorException->getTrace()[1]['class'] === \PDO::class &&
                $errorException->getTrace()[1]['function'] === 'exec'
            ) {
                // intended behavior
                // the consumer also shouldn't be scheduled, but if it is, it'll emit an error message
                return $this;
            }

            throw $errorException;
        }

        throw new \LogicException('Userspace PDO Warned Error failed to fail');
    }

    private function userspacePDOSilentError() : ProducerInterface
    {
        $pdo = $this->getPDO();
        // why would anyone do this
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);

        $this->getApiV1RDBMSConnectionService()->usePDO($pdo);

        $pdo->beginTransaction();

        // if this manages to go through, the consumer will emit an error message
        $consumerJobId = $this
            ->getApiV1WorkerService()
            ->getNewJobScheduler()
            ->setJobTypeCode(Consumer\Facade::JOB_TYPE_CODE)
            ->setWorkAtDateTime(new \DateTime())
            ->save()
            ->getJobId();

        $pdo->exec("INSERT INTO userspace_table (consumer_job_id, event_type, is_expected) VALUES ($consumerJobId, 'userspace_pdo_error_silent', false)");

        // violates uniqueness constraint on consumer_job_id
        $pdo->exec("INSERT INTO userspace_table (consumer_job_id, event_type, is_expected) VALUES ($consumerJobId, 'userspace_pdo_error_silent', false)");

        if ($pdo->errorCode() === '00000') {
            $pdo->rollBack();

            throw new \LogicException('Userspace PDO Silent Error failed to fail');
        }

        // shouldn't do anything, but on the off chance that it does, the consumer will emit an error message
        $pdo->commit();

        return $this;
    }

    private function transactionalJobStateTransition() : ProducerInterface
    {
        $pdo = $this->getPDO();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        try {
            $this->getApiV1RDBMSConnectionService()->usePDO($pdo);

            $pdo->beginTransaction();

            $consumerJobId = $this
                ->getApiV1WorkerService()
                ->getNewJobScheduler()
                ->setJobTypeCode(Consumer\Facade::JOB_TYPE_CODE)
                ->setWorkAtDateTime(new \DateTime())
                ->save()
                ->getJobId();

            $pdo->exec("INSERT INTO userspace_table (consumer_job_id, event_type, is_expected) VALUES ($consumerJobId, 'transactional_job_state_transition', true)");

            $this->getApiV1WorkerService()->requestCompleteSuccess()->applyRequest();

            $pdo->commit();
        } catch (\Throwable $throwable) {
            $pdo->rollBack();

            // the try/catch in the parent will put this job on hold
            throw $throwable;
        }

        return $this;
    }

    private function getPDO() : \PDO
    {
        if ($this->pdo === null) {
            $dataSourceName = sprintf(
                '%s:dbname=%s;host=%s',
                $_ENV['DATABASE_ADAPTER'],
                $_ENV['DATABASE_NAME'],
                $_ENV['DATABASE_HOST']
            );

            $this->pdo = new \PDO(
                $dataSourceName,
                $_ENV['DATABASE_USERNAME'],
                $_ENV['DATABASE_PASSWORD'],
                [
                    \PDO::ATTR_PERSISTENT => true,
                    \PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        }

        return $this->pdo;
    }
}
