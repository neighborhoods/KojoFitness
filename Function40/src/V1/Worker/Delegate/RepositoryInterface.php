<?php
declare(strict_types=1);

namespace Neighborhoods\V1\Worker\Delegate;

use Neighborhoods\V1\Worker\DelegateInterface;

interface RepositoryInterface
{
    public function getV1NewWorkerDelegate(): DelegateInterface;
}
