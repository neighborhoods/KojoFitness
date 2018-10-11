<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction42\V1\Worker\Delegate;

use Neighborhoods\KojoFitnessFunction42\V1\Worker\DelegateInterface;

interface FactoryInterface
{
    public function create(): DelegateInterface;
}
