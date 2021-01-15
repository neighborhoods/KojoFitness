<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase52\V1;

use Neighborhoods\Kojo\Api;

interface WorkerInterface
{
    public function setApiV1WorkerService(Api\V1\Worker\ServiceInterface $apiV1WorkerService);

    public function work() : WorkerInterface;
}
