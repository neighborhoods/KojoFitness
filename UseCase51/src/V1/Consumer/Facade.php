<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase51\V1\Consumer;

use Neighborhoods\Pylon\DependencyInjection;
use Neighborhoods\Kojo\Api;
use Symfony\Component\Finder\Finder;
use Neighborhoods\KojoFitnessUseCase51\V1\ConsumerInterface;

class Facade
{
    use Api\V1\Worker\Service\AwareTrait;
    use Api\V1\RDBMS\Connection\Service\AwareTrait;

    public const JOB_TYPE_CODE = 'kojofitness_jsclp_logging_robustness_tester_consumer';

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

        /** @var ConsumerInterface $worker */
        $worker = $containerBuilder->get(ConsumerInterface::class);
        $worker->setApiV1WorkerService($this->getApiV1WorkerService());
        $worker->setApiV1RDBMSConnectionService($this->getApiV1RDBMSConnectionService());

        $worker->work();

        return $this;
    }
}
