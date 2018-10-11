<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction40\V1\Aws\Sqs\SqsClient;

use Aws\Sqs\SqsClient;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $AwsSqsSqsClient;

    public function setV1AwsSqsSqsClient(SqsClient $awsSqsSqsClient): self
    {
        if ($this->hasV1AwsSqsSqsClient()) {
            throw new \LogicException('AwsSqsSqsClient is already set.');
        }
        $this->AwsSqsSqsClient = $awsSqsSqsClient;

        return $this;
    }

    protected function getV1AwsSqsSqsClient(): SqsClient
    {
        if (!$this->hasV1AwsSqsSqsClient()) {
            throw new \LogicException('AwsSqsSqsClient is not set.');
        }

        return $this->AwsSqsSqsClient;
    }

    protected function hasV1AwsSqsSqsClient(): bool
    {
        return isset($this->AwsSqsSqsClient);
    }

    protected function unsetV1AwsSqsSqsClient(): self
    {
        if (!$this->hasV1AwsSqsSqsClient()) {
            throw new \LogicException('AwsSqsSqsClient is not set.');
        }
        unset($this->AwsSqsSqsClient);

        return $this;
    }
}
