<?php
declare(strict_types=1);

namespace Neighborhoods\V1\Worker\Queue\Message;

use Neighborhoods\V1\Worker\Queue\MessageInterface;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $NeighborhoodsKojoExamplesV1WorkerQueueMessage;

    public function setV1WorkerQueueMessage(MessageInterface $v1WorkerQueueMessage): self
    {
        if ($this->hasV1WorkerQueueMessage()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerQueueMessage is already set.');
        }
        $this->NeighborhoodsKojoExamplesV1WorkerQueueMessage = $v1WorkerQueueMessage;

        return $this;
    }

    protected function getV1WorkerQueueMessage(): MessageInterface
    {
        if (!$this->hasV1WorkerQueueMessage()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerQueueMessage is not set.');
        }

        return $this->NeighborhoodsKojoExamplesV1WorkerQueueMessage;
    }

    protected function hasV1WorkerQueueMessage(): bool
    {
        return isset($this->NeighborhoodsKojoExamplesV1WorkerQueueMessage);
    }

    protected function unsetV1WorkerQueueMessage(): self
    {
        if (!$this->hasV1WorkerQueueMessage()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerQueueMessage is not set.');
        }
        unset($this->NeighborhoodsKojoExamplesV1WorkerQueueMessage);

        return $this;
    }
}
