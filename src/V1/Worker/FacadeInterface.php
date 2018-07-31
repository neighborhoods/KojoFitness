<?php
declare(strict_types=1);

namespace Neighborhoods\KojoExamples\V1\Worker;

interface FacadeInterface
{
    public function start(): FacadeInterface;
}