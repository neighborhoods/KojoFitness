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
            $this->getApiV1WorkerService()->requestCompleteSuccess()->applyRequest();
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
