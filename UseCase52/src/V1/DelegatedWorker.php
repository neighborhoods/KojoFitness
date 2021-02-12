<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase52\V1;

use Neighborhoods\Kojo\Api;

class DelegatedWorker
{
    use Api\V1\Worker\Service\AwareTrait;
    use Api\V1\RDBMS\Connection\Service\AwareTrait;

    /** @var \PDO */
    private $pdo;

    public function work()
    {
        $this->getApiV1RDBMSConnectionService()->usePDO($this->getPDO());

        $jobId = $this->getApiV1WorkerService()->getJobId();

        $jobData = $this->getJobDataByJobId($jobId);

        $this->processJobData($jobData);
    }

    private function getJobDataByJobId(int $jobId) : array
    {
        $jobDataArray = $this
            ->getPDO()
            ->query("SELECT id, model_id, job_id FROM delegator_worker_job_data WHERE job_id = $jobId")
            ->fetchAll();

        if (count($jobDataArray) === 0) {
            throw new \RuntimeException('No job_data for worker ' . $jobId);
        }

        if (count($jobDataArray) > 1) {
            throw new \RuntimeException('Too much job_data for worker ' . $jobId);
        }

        return $jobDataArray[0];
    }

    private function processJobData(array $jobData)
    {
        $pdo = $this->getPDO();

        try {
            $pdo->beginTransaction();

            $this->getApiV1WorkerService()->getLogger()->notice('Delegated worker did some work');

            $this->deleteJobDataById($jobData['id']);

            $this->getApiV1WorkerService()->requestCompleteSuccess()->applyRequest();

            $pdo->commit();

            // log success
        } catch (\Throwable $throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $throwable;
        }
    }

    private function deleteJobDataById(int $jobDataId)
    {
        $this
            ->getPDO()
            ->exec("DELETE FROM delegator_worker_job_data WHERE id = $jobDataId");
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
