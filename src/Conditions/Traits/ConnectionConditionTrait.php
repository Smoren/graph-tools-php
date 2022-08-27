<?php

namespace Smoren\GraphTools\Conditions\Traits;

/**
 * @property array<string>|null $connectionTypesOnly
 * @property array<string> $connectionTypesExclude
 */
trait ConnectionConditionTrait
{
    public function getConnectionTypesOnly(): ?array
    {
        return $this->connectionTypesOnly;
    }

    public function getConnectionTypesExclude(): array
    {
        return $this->connectionTypesExclude;
    }

    public function setConnectionTypesOnly(?array $types): self
    {
        $this->connectionTypesOnly = $types;
        return $this;
    }

    public function setConnectionTypesExclude(array $types): self
    {
        $this->connectionTypesExclude = $types;
        return $this;
    }
}
