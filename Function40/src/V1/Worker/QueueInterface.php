<?php
declare(strict_types=1);

namespace Neighborhoods\V1\Worker;

use Neighborhoods\V1\Worker\Queue\MessageInterface;

interface QueueInterface
{
    public function getNextMessage(): MessageInterface;

    public function hasNextMessage(): bool;

    public function waitForNextMessage(): QueueInterface;
}
