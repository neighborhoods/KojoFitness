<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction41\V2\Worker\Queue\Message;

use Neighborhoods\KojoFitnessFunction41\V2\Worker\Queue\MessageInterface;

/** @codeCoverageIgnore */
interface FactoryInterface
{
    public function create() : MessageInterface;
}
