<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase41\V1\Worker\Delegate;

use Neighborhoods\KojoFitnessUseCase41\V1\Worker\DelegateInterface;

/** @codeCoverageIgnore */
class Factory implements FactoryInterface
{
    use AwareTrait;

    public function create() : DelegateInterface
    {
        return clone $this->getV1WorkerDelegate();
    }
}
