<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase52\V1;

use Neighborhoods\Kojo\Api;

class Delegator
{
    use Api\V1\Worker\Service\AwareTrait;
    use Api\V1\RDBMS\Connection\Service\AwareTrait;

    private const BATCH_SIZE = 100;

    /** @var \PDO */
    private $pdo;

    public function work()
    {
        $this->getApiV1RDBMSConnectionService()->usePDO($this->getPDO());

        while (true) {
            $unitsOfWork = $this->getWorkBatch();

            if (empty($unitsOfWork)) {
                break;
            }

            foreach ($unitsOfWork as $unitOfWork) {
                $this->processUnitOfWork($unitOfWork);
            }
        }

        $this->getApiV1WorkerService()->requestCompleteSuccess()->applyRequest();

        // another delegator will take its place (not in this use case, but in real world usage)
        // either with static scheduling or by doing it here (or both)
    }

    private function getWorkBatch() : array
    {
        $limit = self::BATCH_SIZE;

        return $this
            ->getPDO()
            ->query("SELECT id, model_id FROM work_queue ORDER BY id ASC LIMIT $limit")
            ->fetchAll();
    }

    private function processUnitOfWork(array $unitOfWork)
    {
        $pdo = $this->getPDO();
        $currentWorkId = $unitOfWork['id'];
        $modelId = $unitOfWork['model_id'];

        $jobCount = $this->getCountOfJobDataMatchingModelId($modelId);

        if ($jobCount > 1) {
            throw new \RuntimeException('Multiple workers working on the same data');
        }

        if ($jobCount === 1) {
            // something else is working on this
            return;
        }

        // $jobCount === 0
        try {
            $pdo->beginTransaction();

            $scheduledJob = $this->getApiV1WorkerService()
                ->getNewJobScheduler()
                ->setJobTypeCode('delegated_worker')
                ->setWorkAtDateTime(new \DateTime())
                ->save();

            $this->insertJobData($scheduledJob->getJobId(), $modelId);

            $this->deleteWorkById($currentWorkId);

            $pdo->commit();
        } catch (\Throwable $throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $throwable;
        }
    }

    private function getCountOfJobDataMatchingModelId(int $modelId) : int
    {
        return (int)$this
            ->getPDO()
            ->query("SELECT count(*) AS count FROM delegator_worker_job_data WHERE model_id = $modelId")
            ->fetch()['count'];
    }

    private function insertJobData(int $jobId, int $modelId)
    {
        $this
            ->getPDO()
            ->exec("INSERT INTO delegator_worker_job_data (job_id, model_id) VALUES ($jobId, $modelId)");
    }

    private function deleteWorkById(int $workId)
    {
        $this
            ->getPDO()
            ->exec("DELETE FROM work_queue WHERE id = $workId");
    }

    private function getPDO() : \PDO
    {
        if (!$this->pdo) {
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
