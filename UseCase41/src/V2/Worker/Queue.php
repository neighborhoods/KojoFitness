<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase41\V2\Worker;

use Guzzle\Service\Resource\Model;
use Neighborhoods\KojoFitnessUseCase41\V2;
use Neighborhoods\KojoFitnessUseCase41\V2\Aws;
use Neighborhoods\KojoFitnessUseCase41\V2\Worker\Queue\MessageInterface;

class Queue implements QueueInterface
{
    use V2\Worker\Queue\Message\Factory\AwareTrait;
    use V2\Worker\Queue\Message\AwareTrait;
    use Aws\Sqs\SqsClient\AwareTrait;

    protected $queueUrl;

    protected const QUEUE_URL = 'QueueUrl';
    protected const MESSAGES = 'Messages';

    protected const WAIT_TIME_SECONDS = 'WaitTimeSeconds';

    public function getNextMessage() : MessageInterface
    {
        $v2WorkerQueueMessage = $this->getV2WorkerQueueMessage();
        $this->unsetV2WorkerQueueMessage();

        return $v2WorkerQueueMessage;
    }

    public function waitForNextMessage() : QueueInterface
    {
        $guzzleServiceResourceModel = $this->receiveMessage();
        while ($guzzleServiceResourceModel->get(self::MESSAGES) === null) {
            $guzzleServiceResourceModel = $this->receiveMessage();
        }
        $this->createNextMessage($guzzleServiceResourceModel);

        return $this;
    }

    public function hasNextMessage() : bool
    {
        if (!$this->hasV2WorkerQueueMessage()) {
            $guzzleServiceResourceModel = $this->receiveMessage();
            if ($guzzleServiceResourceModel->get(self::MESSAGES) !== null) {
                $this->createNextMessage($guzzleServiceResourceModel);
            }
        }

        return $this->hasV2WorkerQueueMessage();
    }

    protected function createNextMessage(Model $guzzleServiceResourceModel) : QueueInterface
    {
        $v2WorkerQueueMessage = $this->getV2WorkerQueueMessageFactory()->create();
        $v2WorkerQueueMessage->setGuzzleServiceResourceModel($guzzleServiceResourceModel);
        $this->setV2WorkerQueueMessage($v2WorkerQueueMessage);

        return $this;
    }

    protected function receiveMessage() : Model
    {
        return $this->getV2AwsSqsSqsClient()->receiveMessage(
            [
                self::QUEUE_URL => $this->getQueueUrl(),
                self::WAIT_TIME_SECONDS => 20,
            ]
        );
    }

    public function setQueueUrl(string $queueUrl) : QueueInterface
    {
        if ($this->queueUrl === null) {
            $this->queueUrl = $queueUrl;
        } else {
            throw new \LogicException('Queue URL is already set.');
        }

        return $this;
    }

    protected function getQueueUrl() : string
    {
        if ($this->queueUrl === null) {
            throw new \LogicException('Queue URL is not set.');
        }

        return $this->queueUrl;
    }
}
