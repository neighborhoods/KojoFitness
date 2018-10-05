<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction41\V2\Worker\Delegate;

use Neighborhoods\KojoFitnessFunction41\V2\Worker\DelegateInterface;

interface FactoryInterface
{
    public function create() : DelegateInterface;
}
