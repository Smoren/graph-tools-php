<?php

namespace Smoren\GraphTools\Conditions;

use Smoren\GraphTools\Interfaces\ConnectionConditionInterface;
use Smoren\GraphTools\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Interfaces\VertexConditionInterface;

class FilterCondition implements FilterConditionInterface
{
    /**
     * @var array<string>|null
     */
    protected ?array $connectionTypesOnly = null;
    /**
     * @var array<string>
     */
    protected array $connectionTypesExclude = [];
    /**
     * @var array<string>|null
     */
    protected ?array $vertexTypesOnly = null;
    /**
     * @var array<string>
     */
    protected array $vertexTypesExclude = [];

    public function __construct()
    {
    }

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

    public function getVertexTypesOnly(): ?array
    {
        return $this->vertexTypesOnly;
    }

    public function getVertexTypesExclude(): array
    {
        return $this->vertexTypesExclude;
    }

    public function setVertexTypesOnly(?array $types): self
    {
        $this->vertexTypesOnly = $types;
        return $this;
    }

    public function setVertexTypesExclude(array $types): VertexConditionInterface
    {
        $this->vertexTypesExclude = $types;
        return $this;
    }
}