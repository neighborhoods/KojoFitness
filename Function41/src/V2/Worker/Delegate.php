<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction41\V2\Worker;

use Neighborhoods\KojoFitnessFunction41\V2\Worker;

class Delegate implements DelegateInterface
{
    use Worker\Queue\Message\AwareTrait;

    public function businessLogic() : DelegateInterface
    {
        $this->getV2WorkerQueueMessage()->delete();

        return $this;
    }
}
