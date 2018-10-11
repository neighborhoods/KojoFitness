<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction42\V1\Worker\Delegate\Repository;

use Neighborhoods\KojoFitnessFunction42\V1\Worker\Delegate\RepositoryInterface;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $NeighborhoodsKojoExamplesV1WorkerDelegateRepository;

    public function setV1WorkerDelegateRepository(RepositoryInterface $v1WorkerDelegateRepository): self
    {
        if ($this->hasV1WorkerDelegateRepository()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerDelegateRepository is already set.');
        }
        $this->NeighborhoodsKojoExamplesV1WorkerDelegateRepository = $v1WorkerDelegateRepository;

        return $this;
    }

    protected function getV1WorkerDelegateRepository(): RepositoryInterface
    {
        if (!$this->hasV1WorkerDelegateRepository()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerDelegateRepository is not set.');
        }

        return $this->NeighborhoodsKojoExamplesV1WorkerDelegateRepository;
    }

    protected function hasV1WorkerDelegateRepository(): bool
    {
        return isset($this->NeighborhoodsKojoExamplesV1WorkerDelegateRepository);
    }

    protected function unsetV1WorkerDelegateRepository(): self
    {
        if (!$this->hasV1WorkerDelegateRepository()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV1WorkerDelegateRepository is not set.');
        }
        unset($this->NeighborhoodsKojoExamplesV1WorkerDelegateRepository);

        return $this;
    }
}
