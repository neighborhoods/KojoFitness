<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase41\V2\Worker;

use Neighborhoods\KojoFitnessUseCase41\V2\Worker\Queue\MessageInterface;

interface DelegateInterface
{
    public function setV2WorkerQueueMessage(MessageInterface $workerQueueMessage);

    public function businessLogic() : DelegateInterface;
}
