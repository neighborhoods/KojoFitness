<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction43\V1\Worker\Queue\Message;

use Neighborhoods\KojoFitnessFunction43\V1\Worker\Queue\MessageInterface;

/** @codeCoverageIgnore */
interface FactoryInterface
{
    public function create(): MessageInterface;
}
