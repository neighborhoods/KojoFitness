<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase46\V1\Worker;

use Neighborhoods\Kojo\Api;
use Neighborhoods\KojoFitnessUseCase46\V1\Worker;

class Delegate implements DelegateInterface
{
    use Worker\Queue\Message\AwareTrait;
    use Api\V1\Worker\Service\AwareTrait;

    public function businessLogic() : DelegateInterface
    {
        $i = random_int(1, 60);
        switch (true) {
            case  (1 <= $i) && ($i <= 60):
                $context = [
                    'event_type' => 'task_status',
                    'top_level_status' => 'success',
                    'nested_object' => [
                        'random_letters' => $this->randomLetterString(),
                        'random_word' => $this->randomWord(),
                        'random_value' => $i,
                        'status' => 'success',
                    ],
                ];
                $this->getV1WorkerQueueMessage()->delete();
                $this->getApiV1WorkerService()->getLogger()->alert('Deleted the message boss!', $context);
                break;
//            case (61 <= $i) && ($i <= 80):
//                $this->onlyTakesIntegers('Not very lucky');
//                break;
//            case (81 <= $i) && ($i <= 95):
//                throw new \LogicException('That was not very logical');
//                break;
            case (61 <= $i) && ($i <= 100):
                $this->createDeepPreviousExceptionChain();
                break;
            default:
                throw new \UnexpectedValueException('Do arrays start at 1?');
                break;
        }
        return $this;
    }

    public function onlyTakesIntegers(int $number)
    {
        return $number;
    }

    public function randomLetterString() : string
    {
        $seed = ['mred', 'mris', 'crmls', 'bright'];
        return $this->randomElementFromArray($seed);
    }

    public function randomWord() : string
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
