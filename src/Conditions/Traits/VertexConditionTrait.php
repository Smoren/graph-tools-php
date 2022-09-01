<?php

namespace Smoren\GraphTools\Conditions\Traits;

use Smoren\GraphTools\Conditions\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * Trait for vertex condition implementation
 * @implements VertexConditionInterface<mixed>
 * @property array<string>|null $vertexTypesOnly vertex types whitelist
 * @property array<string> $vertexTypesExclude vertex types blacklist
 * @property array<string>|null $vertexIdsOnly vertex ids whitelist
 * @property array<string> $vertexIdsExclude  vertex ids blacklist
 */
trait VertexConditionTrait
{
    /**
     * @inheritDoc
     */
    public function getVertexTypesOnly(): ?array
    {
        return $this->vertexTypesOnly;
    }

    /**
     * @inheritDoc
     */
    public function getVertexTypesExcluded(): array
    {
        return $this->vertexTypesExclude;
    }

    /**
     * @inheritDoc
     */
    public function getVertexIdsOnly(): ?array
    {
        return $this->vertexIdsOnly;
    }

    /**
     * @inheritDoc
     */
    public function getVertexIdsExcluded(): array
    {
        return $this->vertexIdsExclude;
    }

    /**
     * @inheritDoc
     */
    public function onlyVertexTypes(?array $types): self
    {
        $this->vertexTypesOnly = $types;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function excludeVertexTypes(array $types): self
    {
        $this->vertexTypesExclude = $types;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function onlyVertexIds(?array $ids): self
    {
        $this->vertexIdsOnly = $ids;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function excludeVertexIds(array $ids): self
    {
        $this->vertexIdsExclude = $ids;
        return $this;
    }

    /**
     * @inheritDoc
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
