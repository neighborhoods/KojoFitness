<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction42\V1\Worker;

use Neighborhoods\Pylon\DependencyInjection;
use Neighborhoods\Kojo\Api;
use Neighborhoods\KojoFitnessFunction42\V1\WorkerInterface;
use Symfony\Component\Finder\Finder;

class Facade implements FacadeInterface
{
    use Api\V1\Worker\Service\AwareTrait;
    protected $containerBuilderFacade;
    protected $worker;
    protected $isBootStrapped = false;

    public function start(): FacadeInterface
    {
        $this->bootstrap();
        $this->getWorker()->work();

        return $this;
    }

    protected function bootstrap(): FacadeInterface
    {
        if ($this->isBootStrapped !== false) {
            throw new \LogicException('Worker facade is already bootstrapped.');
        }

        $discoverableDirectories[] = __DIR__ . '/../../../src';
        $discoverableDirectories[] = __DIR__ . '/../../../fab';
        $finder = new Finder();
        $finder->name('*.yml');
        $finder->files()->in($discoverableDirectories);
        $this->getContainerBuilderFacade()->addFinder($finder);
        $containerBuilder = $this->getContainerBuilderFacade()->getContainerBuilder();
        $this->setWorker($containerBuilder->get(WorkerInterface::class));
        $this->isBootStrapped = true;

        return $this;
    }

    public function getContainerBuilderFacade(): DependencyInjection\ContainerBuilder\FacadeInterface
    {
        if ($this->containerBuilderFacade === null) {
            $this->containerBuilderFacade = new DependencyInjection\ContainerBuilder\Facade();
        }

        return $this->containerBuilderFacade;
    }

    protected function getWorker(): WorkerInterface
    {
        if ($this->worker === null) {
            throw new \LogicException('Worker is not set.');
        }

        return $this->worker;
    }

    protected function setWorker(WorkerInterface $worker): FacadeInterface
    {
        if ($this->worker !== null) {
            throw new \LogicException('Worker is already set.');
        }
        $this->worker = $worker->setApiV1WorkerService($this->getApiV1WorkerService());

        return $this;
    }
}
