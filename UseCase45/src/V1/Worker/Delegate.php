<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase45\V1\Worker;

use Neighborhoods\KojoFitnessUseCase45\V1\Worker;

class Delegate implements DelegateInterface
{
    use Worker\Queue\Message\AwareTrait;

    public function businessLogic(): DelegateInterface
    {
        $this->getV1WorkerQueueMessage()->delete();

        return $this;
    }
}
