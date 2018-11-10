<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase41\V2\Worker\Queue\Message;

use Neighborhoods\KojoFitnessUseCase41\V2\Worker\Queue\MessageInterface;


/** @codeCoverageIgnore */
class Factory implements FactoryInterface
{
    use AwareTrait;


    public function create() : MessageInterface
    {
        return clone $this->getV2WorkerQueueMessage();
    }
}
