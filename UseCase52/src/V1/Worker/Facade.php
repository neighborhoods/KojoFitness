<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase52\V1\Worker;

use Neighborhoods\Pylon\DependencyInjection;
use Neighborhoods\Kojo\Api;
use Symfony\Component\Finder\Finder;
use Neighborhoods\KojoFitnessUseCase52\V1\WorkerInterface;

class Facade
{
    use Api\V1\Worker\Service\AwareTrait;

    public const JOB_TYPE_CODE = 'kojofitness_5x_base';

    public function start(): Facade
    {
        $discoverableDirectories[] = __DIR__ . '/../../../src';

        $finder = new Finder();
        $finder
            ->name('*.yml')
            ->files()
            ->in($discoverableDirectories);

        $containerBuilderFacade = new DependencyInjection\ContainerBuilder\Facade();
        $containerBuilderFacade->addFinder($finder);
        $containerBuilder = $containerBuilderFacade->getContainerBuilder();

        /** @var WorkerInterface $worker */
        $worker = $containerBuilder->get(WorkerInterface::class);
        $worker->setApiV1WorkerService($this->getApiV1WorkerService());

        $worker->work();

        return $this;
    }
}
