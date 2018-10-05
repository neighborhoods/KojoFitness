<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction41\V2;

use Neighborhoods\Kojo\Api\V2\Worker\ServiceInterface;

interface WorkerInterface
{
    public function setApiV2WorkerService(ServiceInterface $apiV2WorkerService);

    public function work() : WorkerInterface;
}
