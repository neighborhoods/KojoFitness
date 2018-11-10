<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase41\V2\Worker\Delegate;

use Neighborhoods\KojoFitnessUseCase41\V2\Worker\DelegateInterface;

interface RepositoryInterface
{
    public function getV2NewWorkerDelegate() : DelegateInterface;
}
