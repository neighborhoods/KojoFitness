<?php
declare(strict_types=1);

namespace Neighborhoods\KojoExamples\V1\Worker\Queue\Message;

use Neighborhoods\KojoExamples\V1\Worker\Queue\MessageInterface;

/** @codeCoverageIgnore */
interface FactoryInterface
{
    public function create(): MessageInterface;
}
