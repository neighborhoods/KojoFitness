<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase41\V2\Worker\Queue\Message;

use Neighborhoods\KojoFitnessUseCase41\V2\Worker\Queue\MessageInterface;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $NeighborhoodsKojoExamplesV2WorkerQueueMessage;

    public function setV2WorkerQueueMessage(MessageInterface $v2WorkerQueueMessage) : self
    {
        if ($this->hasV2WorkerQueueMessage()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerQueueMessage is already set.');
        }
        $this->NeighborhoodsKojoExamplesV2WorkerQueueMessage = $v2WorkerQueueMessage;

        return $this;
    }

    protected function getV2WorkerQueueMessage() : MessageInterface
    {
        if (!$this->hasV2WorkerQueueMessage()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerQueueMessage is not set.');
        }

        return $this->NeighborhoodsKojoExamplesV2WorkerQueueMessage;
    }

    protected function hasV2WorkerQueueMessage() : bool
    {
        return isset($this->NeighborhoodsKojoExamplesV2WorkerQueueMessage);
    }

    protected function unsetV2WorkerQueueMessage() : self
    {
        if (!$this->hasV2WorkerQueueMessage()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerQueueMessage is not set.');
        }
        unset($this->NeighborhoodsKojoExamplesV2WorkerQueueMessage);

        return $this;
    }
}
