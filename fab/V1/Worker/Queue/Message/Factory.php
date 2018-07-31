<?php
declare(strict_types=1);

namespace Neighborhoods\KojoExamples\V1\Worker\Queue\Message;

use Neighborhoods\KojoExamples\V1\Worker\Queue\MessageInterface;


/** @codeCoverageIgnore */
class Factory implements FactoryInterface
{
    use AwareTrait;


    public function create(): MessageInterface
    {
        return clone $this->getV1WorkerQueueMessage();
    }
}
