<?php

namespace Smoren\GraphTools\Conditions\Traits;

use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * @property array<string>|null $vertexTypesOnly
 * @property array<string> $vertexTypesExclude
 */
trait VertexConditionTrait
{
    /**
     * @return array<string>|null
     */
    public function getVertexTypesOnly(): ?array
    {
        return $this->vertexTypesOnly;
    }

    /**
     * @return array<string>
     */
    public function getVertexTypesExclude(): array
    {
        return $this->vertexTypesExclude;
    }

    /**
     * @param array<string>|null $types
     * @return self
     */
    public function setVertexTypesOnly(?array $types): self
    {
        $this->vertexTypesOnly = $types;
        return $this;
    }

    /**
     * @param array<string> $types
     * @return self
     */
    public function setVertexTypesExclude(array $types): self
    {
        $this->vertexTypesExclude = $types;
        return $this;
    }

    /**
     * @param VertexInterface $vertex
     * @return bool
     */
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
