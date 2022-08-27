<?php

namespace Smoren\GraphTools\Conditions\Traits;

use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * @property array<string>|null $vertexTypesOnly
 * @property array<string> $vertexTypesExclude
 */
trait VertexConditionTrait
{
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
