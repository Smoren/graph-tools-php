<?php

namespace Smoren\GraphTools\Conditions;

use Smoren\GraphTools\Interfaces\FilterConditionInterface;

class FilterCondition extends VertexCondition implements FilterConditionInterface
{
    /**
     * @var array<string>|null
     */
    protected ?array $connectionTypesOnly = null;
    /**
     * @var array<string>
     */
    protected array $connectionTypesExclude = [];

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
