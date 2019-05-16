<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase46\V1\Worker\Queue\Message;

use Neighborhoods\KojoFitnessUseCase46\V1\Worker\Queue\MessageInterface;

/** @codeCoverageIgnore */
interface FactoryInterface
{
    public function create(): MessageInterface;
}
