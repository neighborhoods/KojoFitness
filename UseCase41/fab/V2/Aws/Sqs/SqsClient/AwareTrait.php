<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase41\V2\Aws\Sqs\SqsClient;

use Aws\Sqs\SqsClient;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $AwsSqsSqsClient;

    public function setV2AwsSqsSqsClient(SqsClient $awsSqsSqsClient) : self
    {
        if ($this->hasV2AwsSqsSqsClient()) {
            throw new \LogicException('AwsSqsSqsClient is already set.');
        }
        $this->AwsSqsSqsClient = $awsSqsSqsClient;

        return $this;
    }

    protected function getV2AwsSqsSqsClient() : SqsClient
    {
        if (!$this->hasV2AwsSqsSqsClient()) {
            throw new \LogicException('AwsSqsSqsClient is not set.');
        }

        return $this->AwsSqsSqsClient;
    }

    protected function hasV2AwsSqsSqsClient() : bool
    {
        return isset($this->AwsSqsSqsClient);
    }

    protected function unsetV2AwsSqsSqsClient() : self
    {
        if (!$this->hasV2AwsSqsSqsClient()) {
            throw new \LogicException('AwsSqsSqsClient is not set.');
        }
        unset($this->AwsSqsSqsClient);

        return $this;
    }
}
