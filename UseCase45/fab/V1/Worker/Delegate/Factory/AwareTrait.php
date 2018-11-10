<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase45\V1\Worker\Delegate\Factory;

use Neighborhoods\KojoFitnessUseCase45\V1\Worker\Delegate\FactoryInterface;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $NeighborhoodsKojoExamplesV1WorkerDelegateFactory;

    public function setV1WorkerDelegateFactory(FactoryInterface $v1WorkerDelegateFactory): self
    {
        if ($this->hasV1WorkerDelegateFactory()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerDelegateFactory is already set.');
        }
        $this->NeighborhoodsKojoExamplesV1WorkerDelegateFactory = $v1WorkerDelegateFactory;

        return $this;
    }

    protected function getV1WorkerDelegateFactory(): FactoryInterface
    {
        if (!$this->hasV1WorkerDelegateFactory()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerDelegateFactory is not set.');
        }

        return $this->NeighborhoodsKojoExamplesV1WorkerDelegateFactory;
    }

    protected function hasV1WorkerDelegateFactory(): bool
    {
        return isset($this->NeighborhoodsKojoExamplesV1WorkerDelegateFactory);
    }

    protected function unsetV1WorkerDelegateFactory(): self
    {
        if (!$this->hasV1WorkerDelegateFactory()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerDelegateFactory is not set.');
        }
        unset($this->NeighborhoodsKojoExamplesV1WorkerDelegateFactory);

        return $this;
    }
}
