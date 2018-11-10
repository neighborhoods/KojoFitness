<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase41\V2\Worker\Delegate\Factory;

use Neighborhoods\KojoFitnessUseCase41\V2\Worker\Delegate\FactoryInterface;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $NeighborhoodsKojoExamplesV2WorkerDelegateFactory;

    public function setV2WorkerDelegateFactory(FactoryInterface $v2WorkerDelegateFactory) : self
    {
        if ($this->hasV2WorkerDelegateFactory()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerDelegateFactory is already set.');
        }
        $this->NeighborhoodsKojoExamplesV2WorkerDelegateFactory = $v2WorkerDelegateFactory;

        return $this;
    }

    protected function getV2WorkerDelegateFactory() : FactoryInterface
    {
        if (!$this->hasV2WorkerDelegateFactory()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerDelegateFactory is not set.');
        }

        return $this->NeighborhoodsKojoExamplesV2WorkerDelegateFactory;
    }

    protected function hasV2WorkerDelegateFactory() : bool
    {
        return isset($this->NeighborhoodsKojoExamplesV2WorkerDelegateFactory);
    }

    protected function unsetV2WorkerDelegateFactory() : self
    {
        if (!$this->hasV2WorkerDelegateFactory()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerDelegateFactory is not set.');
        }
        unset($this->NeighborhoodsKojoExamplesV2WorkerDelegateFactory);

        return $this;
    }
}
