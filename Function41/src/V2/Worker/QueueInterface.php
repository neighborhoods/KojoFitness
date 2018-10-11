<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction41\V2\Worker;

use Neighborhoods\KojoFitnessFunction41\V2\Worker\Queue\MessageInterface;

interface QueueInterface
{
    public function getNextMessage() : MessageInterface;

    public function hasNextMessage() : bool;

    public function waitForNextMessage() : QueueInterface;
}
