<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase53\V1;

use Neighborhoods\Kojo\Api;
use Doctrine\DBAL;

class Worker
{
    use Api\V1\Worker\Service\AwareTrait;

    /** @var \PDO */
    private $pdo;

    /** @var DBAL\Connection */
    private $doctrineConnection;

    private const ENVIRONMENT_VARIABLE_NAME_WORK_TYPE = 'KOJO_FITNESS_USECASE53_WORK_TYPE';
    private const ENVIRONMENT_VARIABLE_NAME_NUMBER_OF_MESSAGES = 'KOJO_FITNESS_USECASE53_NUMBER_OF_MESSAGES';

    private const WORK_TYPE_HAPPY_SHORT = 'happy_short';
    private const WORK_TYPE_HAPPY_LONG = 'happy_long';
    private const WORK_TYPE_BIG_DATABASE_ERROR = 'big_database_error';

    private const DEFAULT_NUMBER_OF_MESSAGES = 1000;

    public function work()
    {
        // alternatives involved branching for every iteration of the loop, this seemed simplest
        $workTypeMethodMap = [
            self::WORK_TYPE_HAPPY_SHORT => 'emitHappyShortMessage',
            self::WORK_TYPE_HAPPY_LONG => 'emitHappyLongMessage',
            self::WORK_TYPE_BIG_DATABASE_ERROR => 'emitBigDatabaseErrorMessage',
        ];

        $methodName = $workTypeMethodMap[$this->getWorkType()] ?? null;

        if ($methodName === null) {
            throw new \RuntimeException('Invalid work type: ' . $this->getWorkType());
        }

        $logger = $this->getApiV1WorkerService()->getLogger();
        // distinguish between different invocations of this worker
        // e.g. we should see n messages with batch_id: 81fac145a309430c250da0d84c44c140461a9471,
        // then n messages with batch_id: 50674afdfd99049a1d5abd9ab34e71b046cbee92 a while later
        $batchIdentifier = bin2hex(random_bytes(20));
        $numMessagesEmitted = 0;

        while ($numMessagesEmitted < $this->getNumberOfMessages()) {
            try {
                $message = $this->$methodName();
                $logger->notice(
                    'KojoFitness UseCase53 notice',
                    [
                        'batch_id' => $batchIdentifier,
                        'iteration' => $numMessagesEmitted,
                        'message' => $message
                    ]
                );
            } catch (\Throwable $throwable) {
                $logger->error(
                    'KojoFitness UseCase53 error',
                    [
                        'batch_id' => $batchIdentifier,
                        'iteration' => $numMessagesEmitted,
                        'exception' => $throwable
                    ]
                );
            }

            $numMessagesEmitted++;
        }

        $this->getApiV1WorkerService()->requestCompleteSuccess()->applyRequest();
    }

    private function emitHappyShortMessage() : string
    {
        return 'This works :)';
    }

    private function emitHappyLongMessage() : string
    {
        // 52000 characters, many multiple's of docker's 16kB line limit
        return str_repeat('abcdefghijklmnopqrstuvwxyz', 2000);
    }

    private function emitBigDatabaseErrorMessage()
    {
        $queryBuilder = $this->getDoctrineConnection()->createQueryBuilder();

        // too big for the INTEGER field, RDBMS will complain
        $numberPlaceholder = $queryBuilder->createNamedParameter(PHP_INT_MAX);
        $dataPlaceholder = $queryBuilder->createNamedParameter(json_encode($this->getBigRecord()));

        $queryBuilder
            ->insert('models')
            ->values(
                [
                    'number' => $numberPlaceholder,
                    'data' => $dataPlaceholder
                ]
            );

        $queryBuilder->execute();
    }

    private function getBigRecord() : array
    {
        $record = [];
        $numFieldsAdded = 0;

        while ($numFieldsAdded < 1000) {
            $record[bin2hex(random_bytes(20))] = bin2hex(random_bytes(20));

            $numFieldsAdded++;
        }

        return $record;
    }

    private function getWorkType() : string
    {
        return getenv(self::ENVIRONMENT_VARIABLE_NAME_WORK_TYPE) ?: self::WORK_TYPE_HAPPY_SHORT;
    }

    private function getNumberOfMessages() : int
    {
        return (int)(getenv(self::ENVIRONMENT_VARIABLE_NAME_NUMBER_OF_MESSAGES) ?: self::DEFAULT_NUMBER_OF_MESSAGES);
    }

    private function getDoctrineConnection()
    {
        if (!$this->doctrineConnection) {
            $serverVersion = $this->getPDO()->getAttribute(\PDO::ATTR_SERVER_VERSION);
            $connectionParameters = ['pdo' => $this->getPDO(), 'serverVersion' => $serverVersion];

            $this->doctrineConnection = DBAL\DriverManager::getConnection($connectionParameters);
        }

        return $this->doctrineConnection;
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
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]
            );
        }

        return $this->pdo;
    }
}
