<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessUseCase42\V1\Guzzle\Service\Resource\Model;

use Guzzle\Service\Resource\Model;

/** @codeCoverageIgnore */
trait AwareTrait
{
    protected $GuzzleServiceResourceModel;

    public function setGuzzleServiceResourceModel(Model $guzzleServiceResourceModel): self
    {
        if ($this->hasGuzzleServiceResourceModel()) {
            throw new \LogicException('GuzzleServiceResourceModel is already set.');
        }
        $this->GuzzleServiceResourceModel = $guzzleServiceResourceModel;

        return $this;
    }

    protected function getGuzzleServiceResourceModel(): Model
    {
        if (!$this->hasGuzzleServiceResourceModel()) {
            throw new \LogicException('GuzzleServiceResourceModel is not set.');
        }

        return $this->GuzzleServiceResourceModel;
    }

    protected function hasGuzzleServiceResourceModel(): bool
    {
        return isset($this->GuzzleServiceResourceModel);
    }

    protected function unsetGuzzleServiceResourceModel(): self
    {
        if (!$this->hasGuzzleServiceResourceModel()) {
            throw new \LogicException('GuzzleServiceResourceModel is not set.');
        }
        unset($this->GuzzleServiceResourceModel);

        return $this;
    }
}
