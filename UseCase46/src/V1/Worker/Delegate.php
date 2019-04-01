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
        $i = random_int(0, 100);
        switch (true) {
            case  (1 <= $i) && ($i <= 80):
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
            case (81 <= $i) && ($i <= 90):
                $this->onlyTakesIntegers('Not very lucky');
                break;
            case (91 <= $i) && ($i <= 100):
                throw new \LogicException('That was not very logical');
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
}
