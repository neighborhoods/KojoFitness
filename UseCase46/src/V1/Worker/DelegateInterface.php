<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase46\V1\Worker;

use Neighborhoods\Kojo\Api\V1\Worker\ServiceInterface;
use Neighborhoods\KojoFitnessUseCase46\V1\Worker\Queue\MessageInterface;

interface DelegateInterface
{
    public function setApiV1WorkerService(ServiceInterface $apiV1WorkerService);

    public function setV1WorkerQueueMessage(MessageInterface $workerQueueMessage);

    public function businessLogic(): DelegateInterface;
}
