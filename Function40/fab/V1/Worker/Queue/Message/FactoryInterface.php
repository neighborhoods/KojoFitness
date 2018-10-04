<?php
declare(strict_types=1);

namespace Neighborhoods\V1\Worker\Queue\Message;

use Neighborhoods\V1\Worker\Queue\MessageInterface;

/** @codeCoverageIgnore */
interface FactoryInterface
{
    public function create(): MessageInterface;
}
