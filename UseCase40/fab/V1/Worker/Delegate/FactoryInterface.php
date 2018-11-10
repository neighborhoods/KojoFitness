<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase40\V1\Worker\Delegate;

use Neighborhoods\KojoFitnessUseCase40\V1\Worker\DelegateInterface;

interface FactoryInterface
{
    public function create(): DelegateInterface;
}
