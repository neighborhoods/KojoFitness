<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase41\V2\Worker\Queue;

use Neighborhoods\KojoFitnessUseCase41\V2\Worker\QueueInterface;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $NeighborhoodsKojoExamplesV2WorkerQueue;

    public function setV2WorkerQueue(QueueInterface $v2WorkerQueue) : self
    {
        if ($this->hasV2WorkerQueue()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerQueue is already set.');
        }
        $this->NeighborhoodsKojoExamplesV2WorkerQueue = $v2WorkerQueue;

        return $this;
    }

    protected function getV2WorkerQueue() : QueueInterface
    {
        if (!$this->hasV2WorkerQueue()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerQueue is not set.');
        }

        return $this->NeighborhoodsKojoExamplesV2WorkerQueue;
    }

    protected function hasV2WorkerQueue() : bool
    {
        return isset($this->NeighborhoodsKojoExamplesV2WorkerQueue);
    }

    protected function unsetV2WorkerQueue() : self
    {
        if (!$this->hasV2WorkerQueue()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerQueue is not set.');
        }
        unset($this->NeighborhoodsKojoExamplesV2WorkerQueue);

        return $this;
    }
}
