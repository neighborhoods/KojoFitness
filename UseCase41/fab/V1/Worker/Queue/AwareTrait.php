<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase41\V1\Worker\Queue;

use Neighborhoods\KojoFitnessUseCase41\V1\Worker\QueueInterface;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $NeighborhoodsKojoExamplesV1WorkerQueue;

    public function setV1WorkerQueue(QueueInterface $v1WorkerQueue) : self
    {
        if ($this->hasV1WorkerQueue()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerQueue is already set.');
        }
        $this->NeighborhoodsKojoExamplesV1WorkerQueue = $v1WorkerQueue;

        return $this;
    }

    protected function getV1WorkerQueue() : QueueInterface
    {
        if (!$this->hasV1WorkerQueue()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerQueue is not set.');
        }

        return $this->NeighborhoodsKojoExamplesV1WorkerQueue;
    }

    protected function hasV1WorkerQueue() : bool
    {
        return isset($this->NeighborhoodsKojoExamplesV1WorkerQueue);
    }

    protected function unsetV1WorkerQueue() : self
    {
        if (!$this->hasV1WorkerQueue()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerQueue is not set.');
        }
        unset($this->NeighborhoodsKojoExamplesV1WorkerQueue);

        return $this;
    }
}
