<?php

namespace Smoren\GraphTools\Conditions;

use Smoren\GraphTools\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Interfaces\VertexInterface;

class VertexCondition implements VertexConditionInterface
{
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

    public function setVertexTypesExclude(array $types): self
    {
        $this->vertexTypesExclude = $types;
        return $this;
    }

    public function isSuitableVertex(VertexInterface $vertex): bool
    {
        if($this->vertexTypesOnly !== null && !in_array($vertex->getType(), $this->vertexTypesOnly)) {
            return false;
        }

        if(in_array($vertex->getType(), $this->vertexTypesExclude)) {
            return false;
        }

        return true;
    }
}
