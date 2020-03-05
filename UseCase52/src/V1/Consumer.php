<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase52\V1;

use Neighborhoods\Kojo\Api;

class Consumer implements ConsumerInterface
{
    use Api\V1\Worker\Service\AwareTrait;
    use Api\V1\RDBMS\Connection\Service\AwareTrait;

    public function work() : ConsumerInterface
    {
        $jobId = $this->getApiV1WorkerService()->getJobId();

        $pdo = $this->constructPDO();

        $results = $pdo->query("SELECT event_type, is_expected FROM userspace_table WHERE consumer_job_id = $jobId")->fetchAll();

        if (count($results) === 0) {
            throw new \LogicException('Consumer should not be scheduled unless it has work in the userspace_table to do');
        }

        if (count($results) > 1) {
            throw new \LogicException('There should be a uniqueness constraint on userspace_table.consumer_job_id');
        }

        $event = $results[0];

        if ($event['is_expected']) {
            $this->getApiV1WorkerService()->getLogger()->notice("Successfully consumed {$event['event_type']} event");
        } else {
            $this->getApiV1WorkerService()->getLogger()->error("Consumed unexpected event of type {$event['event_type']}");
        }

        $this->getApiV1RDBMSConnectionService()->usePDO($pdo);

        $pdo->beginTransaction();

        $pdo->exec("DELETE FROM userspace_table WHERE consumer_job_id = $jobId");

        $this->getApiV1WorkerService()->requestCompleteSuccess()->applyRequest();
        $this->getApiV1WorkerService()->getLogger()->notice(
            'log message to check memory version of assigned state',
            ['requested_state' => 'complete_success']
        );

        // Do weird things to dsync the Memory version of the the job and the persistence layer
        $pdo->rollBack();
        $this->getApiV1WorkerService()->getLogger()->notice(
            'post-rollback',
            ['requested_state' => 'complete_success']
        );

        // Calling this method prevents the alert message
        $this->getApiV1WorkerService()->reload();


        $this->getApiV1WorkerService()->getLogger()->notice(
            'post-reload',
            ['requested_state' => 'complete_success']
        );

        $pdo->beginTransaction();
        $this->getApiV1WorkerService()->requestHold()->applyRequest();
        $this->getApiV1WorkerService()->getLogger()->notice(
            'second attempt',
            ['requested_state' => 'hold']
        );
        $pdo->commit();

        return $this;
    }

    private function constructPDO() : \PDO
    {
        $dataSourceName = sprintf(
            '%s:dbname=%s;host=%s',
            $_ENV['DATABASE_ADAPTER'],
            $_ENV['DATABASE_NAME'],
            $_ENV['DATABASE_HOST']
        );

        return new \PDO(
            $dataSourceName,
            $_ENV['DATABASE_USERNAME'],
            $_ENV['DATABASE_PASSWORD'],
            [
                \PDO::ATTR_PERSISTENT => true,
                \PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    }
}
