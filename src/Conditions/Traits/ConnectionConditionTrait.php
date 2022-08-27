<?php

namespace Smoren\GraphTools\Conditions\Traits;

use Smoren\GraphTools\Conditions\ConnectionCondition;
use Smoren\GraphTools\Conditions\FilterCondition;

/**
 * @property array<string>|null $connectionTypesOnly
 * @property array<string> $connectionTypesExclude
 */
trait ConnectionConditionTrait
{
    /**
     * @return array<string>|null
     */
    public function getConnectionTypesOnly(): ?array
    {
        return $this->connectionTypesOnly;
    }

    /**
     * @return array<string>
     */
    public function getConnectionTypesExclude(): array
    {
        return $this->connectionTypesExclude;
    }

    /**
     * @param array<string>|null $types
     * @return self
     */
    public function setConnectionTypesOnly(?array $types): self
    {
        $this->connectionTypesOnly = $types;
        return $this;
    }

    /**
     * @param array<string> $types
     * @return self
     */
    public function setConnectionTypesExclude(array $types): self
    {
        $this->connectionTypesExclude = $types;
        return $this;
    }
}
