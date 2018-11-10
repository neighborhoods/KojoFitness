<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase41\V2\Worker\Queue\Message\Factory;

use Neighborhoods\KojoFitnessUseCase41\V2\Worker\Queue\Message\FactoryInterface;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $NeighborhoodsKojoExamplesV2WorkerQueueMessageFactory;

    public function setV2WorkerQueueMessageFactory(FactoryInterface $v2WorkerQueueMessageFactory) : self
    {
        if ($this->hasV2WorkerQueueMessageFactory()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerQueueMessageFactory is already set.');
        }
        $this->NeighborhoodsKojoExamplesV2WorkerQueueMessageFactory = $v2WorkerQueueMessageFactory;

        return $this;
    }

    protected function getV2WorkerQueueMessageFactory() : FactoryInterface
    {
        if (!$this->hasV2WorkerQueueMessageFactory()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerQueueMessageFactory is not set.');
        }

        return $this->NeighborhoodsKojoExamplesV2WorkerQueueMessageFactory;
    }

    protected function hasV2WorkerQueueMessageFactory() : bool
    {
        return isset($this->NeighborhoodsKojoExamplesV2WorkerQueueMessageFactory);
    }

    protected function unsetV2WorkerQueueMessageFactory() : self
    {
        if (!$this->hasV2WorkerQueueMessageFactory()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerQueueMessageFactory is not set.');
        }
        unset($this->NeighborhoodsKojoExamplesV2WorkerQueueMessageFactory);

        return $this;
    }
}
