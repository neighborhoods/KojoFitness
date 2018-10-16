<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction43\V1\Worker\Delegate;

use Neighborhoods\KojoFitnessFunction43\V1\Worker\DelegateInterface;

interface FactoryInterface
{
    public function create(): DelegateInterface;
}
