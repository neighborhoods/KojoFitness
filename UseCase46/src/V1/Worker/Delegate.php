<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase46\V1\Worker;

use Neighborhoods\KojoFitnessUseCase46\V1\Worker;

class Delegate implements DelegateInterface
{
    use Worker\Queue\Message\AwareTrait;

    public function businessLogic() : DelegateInterface
    {
        $i = rand(1, 100);
        switch (true) {
            case  (1 <= $i) && ($i <= 50):
                $this->getV1WorkerQueueMessage()->delete();
                break;
            case (51 <= $i) && ($i <= 70):
                $this->onlyTakesIntegers('Not very lucky');
                break;
            case (71 <= $i) && ($i <= 100):
                throw new \LogicException('That want very logical');
            default:
                throw new \UnexpectedValueException('Do arrays start at 1?');

        }
        return $this;
    }

    public function onlyTakesIntegers(int $number)
    {
        return $number;
    }
}
