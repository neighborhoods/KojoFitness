<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase42\V1\Worker;

use Neighborhoods\KojoFitnessUseCase42\V1\Worker\Queue\MessageInterface;

interface DelegateInterface
{
    public function setV1WorkerQueueMessage(MessageInterface $workerQueueMessage);

    public function businessLogic(): DelegateInterface;
}
