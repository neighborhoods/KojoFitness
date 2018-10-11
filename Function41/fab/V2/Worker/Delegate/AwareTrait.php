<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction41\V2\Worker\Delegate;

use Neighborhoods\KojoFitnessFunction41\V2\Worker\DelegateInterface;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $NeighborhoodsKojoExamplesV2WorkerDelegate;

    public function setV2WorkerDelegate(DelegateInterface $v2WorkerDelegate) : self
    {
        if ($this->hasV2WorkerDelegate()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerDelegate is already set.');
        }
        $this->NeighborhoodsKojoExamplesV2WorkerDelegate = $v2WorkerDelegate;

        return $this;
    }

    protected function getV2WorkerDelegate() : DelegateInterface
    {
        if (!$this->hasV2WorkerDelegate()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerDelegate is not set.');
        }

        return $this->NeighborhoodsKojoExamplesV2WorkerDelegate;
    }

    protected function hasV2WorkerDelegate() : bool
    {
        return isset($this->NeighborhoodsKojoExamplesV2WorkerDelegate);
    }

    protected function unsetV2WorkerDelegate() : self
    {
        if (!$this->hasV2WorkerDelegate()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerDelegate is not set.');
        }
        unset($this->NeighborhoodsKojoExamplesV2WorkerDelegate);

        return $this;
    }
}
