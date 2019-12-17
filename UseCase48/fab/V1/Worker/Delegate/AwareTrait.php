<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase48\V1\Worker\Delegate;

use Neighborhoods\KojoFitnessUseCase48\V1\Worker\DelegateInterface;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $NeighborhoodsKojoExamplesV1WorkerDelegate;

    public function setV1WorkerDelegate(DelegateInterface $v1WorkerDelegate): self
    {
        if ($this->hasV1WorkerDelegate()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerDelegate is already set.');
        }
        $this->NeighborhoodsKojoExamplesV1WorkerDelegate = $v1WorkerDelegate;

        return $this;
    }

    protected function getV1WorkerDelegate(): DelegateInterface
    {
        if (!$this->hasV1WorkerDelegate()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerDelegate is not set.');
        }

        return $this->NeighborhoodsKojoExamplesV1WorkerDelegate;
    }

    protected function hasV1WorkerDelegate(): bool
    {
        return isset($this->NeighborhoodsKojoExamplesV1WorkerDelegate);
    }

    protected function unsetV1WorkerDelegate(): self
    {
        if (!$this->hasV1WorkerDelegate()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerDelegate is not set.');
        }
        unset($this->NeighborhoodsKojoExamplesV1WorkerDelegate);

        return $this;
    }
}
