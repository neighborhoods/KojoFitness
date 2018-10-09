<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction41\V2\Worker\Delegate;

use Neighborhoods\KojoFitnessFunction41\V2\Worker\DelegateInterface;
use Neighborhoods\KojoFitnessFunction41\V2\Worker;

class Repository implements RepositoryInterface
{
    use Worker\Delegate\Factory\AwareTrait;

    public function getV2NewWorkerDelegate() : DelegateInterface
    {
        return $this->getV2WorkerDelegateFactory()->create();
    }
}
