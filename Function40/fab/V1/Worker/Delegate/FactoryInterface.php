<?php
declare(strict_types=1);

namespace Neighborhoods\V1\Worker\Delegate;

use Neighborhoods\V1\Worker\DelegateInterface;

interface FactoryInterface
{
    public function create(): DelegateInterface;
}
