<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase47\V1\Worker;

use Neighborhoods\Kojo\Api;
use Neighborhoods\KojoFitnessUseCase47\V1\Worker;

class Delegate implements DelegateInterface
{
    use Worker\Queue\Message\AwareTrait;
    use Api\V1\Worker\Service\AwareTrait;

    public function businessLogic() : DelegateInterface
    {
        $iterations = 0;

        while (true) {
            // Infinite Loop worker
            $context = [
                'event_type' => 'task_status',
                'top_level_status' => 'success',
                'use_case' => 'UseCase47',
                'job_id' => $this->getApiV1WorkerService()->getJobId(),
                'iterations' => $iterations,
                'nested_object' => [
                    'random_letters' => $this->randomLetterString(),
                    'random_word' => $this->randomWord(),
                    'random_value' => random_int(1, 100),
                    'status' => 'success',
                ],
            ];
            $this->getApiV1WorkerService()->getLogger()->notice('I can do this forever', $context);

            $iterations++;
            sleep(3);
        }

        return $this;
    }

    protected function onlyTakesIntegers(int $number)
    {
        return $number;
    }

    protected function randomLetterString() : string
    {
        $seed = ['mred', 'mris', 'crmls', 'bright'];
        return $this->randomElementFromArray($seed);
    }

    protected function randomWord() : string
    {
        $seed = ['validation', 'ingestion', 'materialization'];
        return $this->randomElementFromArray($seed);
    }


    protected function randomElementFromArray(array $seed)
    {
        return $seed[random_int(0, count($seed) - 1)];
    }

    protected function createDeepPreviousExceptionChain()
    {
        try {
            try {
                try {
                    try {
                        try {
                            try {
                                try {
                                    try {
                                        try {
                                            try {
                                                try {
                                                    try {
                                                        try {
                                                            throw new \BadMethodCallException('root cause');
                                                        } catch (\BadMethodCallException $exception) {
                                                            throw new \OverflowException('Deeper', 1, $exception);
                                                        }
                                                    } catch (\OverflowException $exception) {
                                                        throw new \ArithmeticError('The Kick', 2, $exception);
                                                    }
                                                } catch (\ArithmeticError $exception) {
                                                    throw new \OutOfBoundsException(
                                                        'This is not the last one',
                                                        3,
                                                        $exception
                                                    );
                                                }
                                            } catch (\ArithmeticError $exception) {
                                                throw new \OutOfBoundsException(
                                                    'This is not the last one',
                                                    3,
                                                    $exception
                                                );
                                            }
                                        } catch (\OutOfBoundsException $exception) {
                                            throw new \OutOfBoundsException('This is not the last one', 4, $exception);
                                        }
                                    } catch (\OutOfBoundsException $exception) {
                                        throw new \OutOfBoundsException('This is not the last one', 5, $exception);
                                    }
                                } catch (\OutOfBoundsException $exception) {
                                    throw new \OutOfBoundsException('This is not the last one', 6, $exception);
                                }
                            } catch (\OutOfBoundsException $exception) {
                                throw new \OutOfBoundsException('This is not the last one', 7, $exception);
                            }
                        } catch (\OutOfBoundsException $exception) {
                            throw new \OutOfBoundsException('This is not the last one', 8, $exception);
                        }
                    } catch (\OutOfBoundsException $exception) {
                        throw new \OutOfBoundsException('This is not the last one', 9, $exception);
                    }
                } catch (\OutOfBoundsException $exception) {
                    throw new \OutOfBoundsException('This is not the last one', 10, $exception);
                }
            } catch (\OutOfBoundsException $exception) {
                throw new \OutOfBoundsException('This is not the last one', 11, $exception);
            }
        } catch (\OutOfBoundsException $exception) {
            throw new \OutOfBoundsException('This is the last one', 12, $exception);
        }
    }
}
