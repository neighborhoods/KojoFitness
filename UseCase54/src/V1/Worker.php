<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase54\V1;

use Neighborhoods\Kojo\Api;

class Worker implements WorkerInterface
{
    use Api\V1\Worker\Service\AwareTrait;

    public function work(): WorkerInterface
    {
        $workerService = $this->getApiV1WorkerService();

        $symfonyVersion = \Composer\InstalledVersions::getPrettyVersion('symfony/dependency-injection');

        $workerService->getLogger()->info(
            sprintf('UC54: Kojo running successfully with Symfony DI %s (PHP %s)', $symfonyVersion, PHP_VERSION),
            [
                'job_id'          => $workerService->getJobId(),
                'times_crashed'   => $workerService->getTimesCrashed(),
                'symfony_di'      => $symfonyVersion,
            ]
        );

        $workerService->requestCompleteSuccess();
        $workerService->applyRequest();

        return $this;
    }
}
