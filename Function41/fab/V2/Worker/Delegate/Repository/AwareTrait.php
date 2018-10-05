<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction41\V2\Worker\Delegate\Repository;

use Neighborhoods\KojoFitnessFunction41\V2\Worker\Delegate\RepositoryInterface;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $NeighborhoodsKojoExamplesV2WorkerDelegateRepository;

    public function setV2WorkerDelegateRepository(RepositoryInterface $v2WorkerDelegateRepository) : self
    {
        if ($this->hasV2WorkerDelegateRepository()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerDelegateRepository is already set.');
        }
        $this->NeighborhoodsKojoExamplesV2WorkerDelegateRepository = $v2WorkerDelegateRepository;

        return $this;
    }

    protected function getV2WorkerDelegateRepository() : RepositoryInterface
    {
        if (!$this->hasV2WorkerDelegateRepository()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerDelegateRepository is not set.');
        }

        return $this->NeighborhoodsKojoExamplesV2WorkerDelegateRepository;
    }

    protected function hasV2WorkerDelegateRepository() : bool
    {
        return isset($this->NeighborhoodsKojoExamplesV2WorkerDelegateRepository);
    }

    protected function unsetV2WorkerDelegateRepository() : self
    {
        if (!$this->hasV2WorkerDelegateRepository()) {
            throw new \LogicException('NeighborhoodsKojoExamplesV2WorkerDelegateRepository is not set.');
        }
        unset($this->NeighborhoodsKojoExamplesV2WorkerDelegateRepository);

        return $this;
    }
}
