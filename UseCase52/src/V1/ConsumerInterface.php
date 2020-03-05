<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase52\V1;

use Neighborhoods\Kojo\Api;

interface ConsumerInterface
{
    public function setApiV1WorkerService(Api\V1\Worker\ServiceInterface $apiV1WorkerService);

    public function setApiV1RDBMSConnectionService(Api\V1\RDBMS\Connection\ServiceInterface $ApiV1RDBMSConnectionService);

    public function work() : ConsumerInterface;
}
