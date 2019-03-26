<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase46\V1\Worker;

use Guzzle\Service\Resource\Model;
use Neighborhoods\KojoFitnessUseCase46\V1;
use Neighborhoods\KojoFitnessUseCase46\V1\Aws;
use Neighborhoods\KojoFitnessUseCase46\V1\Worker\Queue\MessageInterface;

class Queue implements QueueInterface
{
    use V1\Worker\Queue\Message\Factory\AwareTrait;
    use V1\Worker\Queue\Message\AwareTrait;
    use Aws\Sqs\SqsClient\AwareTrait;

    protected $queueUrl;

    protected const QUEUE_URL = 'QueueUrl';
    protected const MESSAGES = 'Messages';

    protected const WAIT_TIME_SECONDS = 'WaitTimeSeconds';

    public function getNextMessage(): MessageInterface
    {
        $v1WorkerQueueMessage = $this->getV1WorkerQueueMessage();
        $this->unsetV1WorkerQueueMessage();

        return $v1WorkerQueueMessage;
    }

    public function waitForNextMessage(): QueueInterface
    {
        $guzzleServiceResourceModel = $this->receiveMessage();
        while ($guzzleServiceResourceModel->get(self::MESSAGES) === null) {
            $guzzleServiceResourceModel = $this->receiveMessage();
        }
        $this->createNextMessage($guzzleServiceResourceModel);

        return $this;
    }

    public function hasNextMessage(): bool
    {
        if (!$this->hasV1WorkerQueueMessage()) {
            $guzzleServiceResourceModel = $this->receiveMessage();
            if ($guzzleServiceResourceModel->get(self::MESSAGES) !== null) {
                $this->createNextMessage($guzzleServiceResourceModel);
            }
        }

        return $this->hasV1WorkerQueueMessage();
    }

    protected function createNextMessage(Model $guzzleServiceResourceModel): QueueInterface
    {
        $v1WorkerQueueMessage = $this->getV1WorkerQueueMessageFactory()->create();
        $v1WorkerQueueMessage->setGuzzleServiceResourceModel($guzzleServiceResourceModel);
        $this->setV1WorkerQueueMessage($v1WorkerQueueMessage);

        return $this;
    }

    protected function receiveMessage(): Model
    {
        return $this->getV1AwsSqsSqsClient()->receiveMessage(
            [
                self::QUEUE_URL => $this->getQueueUrl(),
                self::WAIT_TIME_SECONDS => 20,
            ]
        );
    }

    public function setQueueUrl(string $queueUrl): QueueInterface
    {
        if ($this->queueUrl === null) {
            $this->queueUrl = $queueUrl;
        } else {
            throw new \LogicException('Queue URL is already set.');
        }

        return $this;
    }

    protected function getQueueUrl(): string
    {
        if ($this->queueUrl === null) {
            throw new \LogicException('Queue URL is not set.');
        }

        return $this->queueUrl;
    }
}
