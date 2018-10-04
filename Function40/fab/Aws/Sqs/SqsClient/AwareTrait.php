<?php
declare(strict_types=1);

namespace Neighborhoods\KojoExamples\Aws\Sqs\SqsClient;

use Aws\Sqs\SqsClient;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $AwsSqsSqsClient;

    public function setAwsSqsSqsClient(SqsClient $awsSqsSqsClient): self
    {
        if ($this->hasAwsSqsSqsClient()) {
            throw new \LogicException('AwsSqsSqsClient is already set.');
        }
        $this->AwsSqsSqsClient = $awsSqsSqsClient;

        return $this;
    }

    protected function getAwsSqsSqsClient(): SqsClient
    {
        if (!$this->hasAwsSqsSqsClient()) {
            throw new \LogicException('AwsSqsSqsClient is not set.');
        }

        return $this->AwsSqsSqsClient;
    }

    protected function hasAwsSqsSqsClient(): bool
    {
        return isset($this->AwsSqsSqsClient);
    }

    protected function unsetAwsSqsSqsClient(): self
    {
        if (!$this->hasAwsSqsSqsClient()) {
            throw new \LogicException('AwsSqsSqsClient is not set.');
        }
        unset($this->AwsSqsSqsClient);

        return $this;
    }
}
