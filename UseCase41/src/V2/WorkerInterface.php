<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase41\V2;

use Neighborhoods\Kojo\Api\V1\Worker\ServiceInterface;

interface WorkerInterface
{
    public function setApiV1WorkerService(ServiceInterface $apiV2WorkerService);

    public function work() : WorkerInterface;
}
