<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction43\V1\Worker\Queue;

use Guzzle\Service\Resource\Model;

interface MessageInterface
{
    public function setGuzzleServiceResourceModel(Model $guzzleServiceResourceModel);

    public function delete(): MessageInterface;

    public function setQueueUrl($queueUrl): MessageInterface;
}
