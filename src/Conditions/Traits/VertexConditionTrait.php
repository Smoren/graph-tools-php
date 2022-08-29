<?php

namespace Smoren\GraphTools\Conditions\Traits;

use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * @property array<string>|null $vertexTypesOnly
 * @property array<string> $vertexTypesExclude
 * @property array<string>|null $vertexIdsOnly
 * @property array<string> $vertexIdsExclude
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
     * @return array<string>|null
     */
    public function getVertexIdsOnly(): ?array
    {
        return $this->vertexIdsOnly;
    }

    /**
     * @return array<string>
     */
    public function getVertexIdsExclude(): array
    {
        return $this->vertexIdsExclude;
    }

    /**
     * @param array<string>|null $types
     * @return self
     */
    public function onlyVertexTypes(?array $types): self
    {
        $this->vertexTypesOnly = $types;
        return $this;
    }

    /**
     * @param array<string> $types
     * @return self
     */
    public function excludeVertexTypes(array $types): self
    {
        $this->vertexTypesExclude = $types;
        return $this;
    }

    /**
     * @param array<string>|null $ids
     * @return self
     */
    public function onlyVertexIds(?array $ids): self
    {
        $this->vertexIdsOnly = $ids;
        return $this;
    }

    /**
     * @param array<string> $ids
     * @return self
     */
    public function excludeVertexIds(array $ids): self
    {
        $this->vertexIdsExclude = $ids;
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

        if($this->vertexIdsOnly !== null && !in_array($vertex->getId(), $this->vertexIdsOnly)) {
            return false;
        }

        if(in_array($vertex->getType(), $this->vertexTypesExclude)) {
            return false;
        }

        if(in_array($vertex->getId(), $this->vertexIdsExclude)) {
            return false;
        }

        return true;
    }
}
