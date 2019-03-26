<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase46\V1\Worker\Queue\Message\Factory;

use Neighborhoods\KojoFitnessUseCase46\V1\Worker\Queue\Message\FactoryInterface;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $NeighborhoodsKojoExamplesV1WorkerQueueMessageFactory;

    public function setV1WorkerQueueMessageFactory(FactoryInterface $v1WorkerQueueMessageFactory): self
    {
        if ($this->hasV1WorkerQueueMessageFactory()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerQueueMessageFactory is already set.');
        }
        $this->NeighborhoodsKojoExamplesV1WorkerQueueMessageFactory = $v1WorkerQueueMessageFactory;

        return $this;
    }

    protected function getV1WorkerQueueMessageFactory(): FactoryInterface
    {
        if (!$this->hasV1WorkerQueueMessageFactory()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerQueueMessageFactory is not set.');
        }

        return $this->NeighborhoodsKojoExamplesV1WorkerQueueMessageFactory;
    }

    protected function hasV1WorkerQueueMessageFactory(): bool
    {
        return isset($this->NeighborhoodsKojoExamplesV1WorkerQueueMessageFactory);
    }

    protected function unsetV1WorkerQueueMessageFactory(): self
    {
        if (!$this->hasV1WorkerQueueMessageFactory()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerQueueMessageFactory is not set.');
        }
        unset($this->NeighborhoodsKojoExamplesV1WorkerQueueMessageFactory);

        return $this;
    }
}
