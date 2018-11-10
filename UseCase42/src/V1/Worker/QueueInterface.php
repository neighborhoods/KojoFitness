<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase42\V1\Worker;

use Neighborhoods\KojoFitnessUseCase42\V1\Worker\Queue\MessageInterface;

interface QueueInterface
{
    public function getNextMessage(): MessageInterface;

    public function hasNextMessage(): bool;

    public function waitForNextMessage(): QueueInterface;
}
