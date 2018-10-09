<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction42\V1\Worker\Delegate;

use Neighborhoods\KojoFitnessFunction42\V1\Worker\DelegateInterface;
use Neighborhoods\KojoFitnessFunction42\V1\Worker;

class Repository implements RepositoryInterface
{
    use Worker\Delegate\Factory\AwareTrait;

    public function getV1NewWorkerDelegate(): DelegateInterface
    {
        return $this->getV1WorkerDelegateFactory()->create();
    }
}
