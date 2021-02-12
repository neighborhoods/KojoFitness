<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase52\V1;

use Neighborhoods\Kojo\Api;

class UserspaceMutexedWorker
{
    use Api\V1\Worker\Service\AwareTrait;
    use Api\V1\RDBMS\Connection\Service\AwareTrait;

    private const NUMBER_OF_UNITS_OF_WORK_TO_DO_TOTAL = 100;
    private const BATCH_SIZE = 100;
    private const MUTEX_PREFIX = 'model/';

    /** @var \PDO */
    private $pdo;

    public function work()
    {
        $unitsWorked = 0;
        $minWorkId = 0;

        $this->getApiV1RDBMSConnectionService()->usePDO($this->getPDO());

        while ($unitsWorked < self::NUMBER_OF_UNITS_OF_WORK_TO_DO_TOTAL) {
            $unitsOfWork = $this->getWorkBatch($minWorkId);

            if (empty($unitsOfWork)) {
                break;
            }

            foreach ($unitsOfWork as $unitOfWork) {
                if ($this->processUnitOfWork($unitOfWork)) {
                    // this may exceed NUMBER_OF_UNITS_OF_WORK_TO_DO_TOTAL
                    // but we're fine with that in this case
                    // (the "heavy lifting" of pulling records from RDBMS is already done)
                    // (feel free to re-arrange this logic if you're not okay with that)
                    $unitsWorked++;
                }
                $minWorkId = $unitOfWork['id'];
            }
        }

        $this->getApiV1WorkerService()->requestCompleteSuccess()->applyRequest();

        if ($unitsWorked >= self::NUMBER_OF_UNITS_OF_WORK_TO_DO_TOTAL) {
            // scale horizontally while there's more work to do
            // (this will be capped by the schedule limit)
            $this
                ->getApiV1WorkerService()
                ->getNewJobScheduler()
                ->setJobTypeCode('userspace_mutexed_worker')
                ->setWorkAtDateTime(new \DateTime())
                ->save();
            $this
                ->getApiV1WorkerService()
                ->getNewJobScheduler()
                ->setJobTypeCode('userspace_mutexed_worker')
                ->setWorkAtDateTime(new \DateTime())
                ->save();
        }
    }

    private function getWorkBatch(int $minId) : array
    {
        $limit = self::BATCH_SIZE;

        return $this
            ->getPDO()
            ->query("SELECT id, model_id FROM work_queue WHERE id > $minId ORDER BY id ASC LIMIT $limit")
            ->fetchAll();
    }

    private function processUnitOfWork(array $unitOfWork) : bool
    {
        $workId = $unitOfWork['id'];
        $modelId = $unitOfWork['model_id'];

        $successfullyAcquiredMutex = $this
            ->getApiV1WorkerService()
            ->tryAcquireUserDefinedMutex(self::MUTEX_PREFIX . $modelId);

        if (!$successfullyAcquiredMutex) {
            // something else is working on this model
            return false;
        }

        // do work

        $this->deleteWorkById($workId);

        $this
            ->getApiV1WorkerService()
            ->releaseUserDefinedMutex(self::MUTEX_PREFIX . $modelId);

        return true;
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
