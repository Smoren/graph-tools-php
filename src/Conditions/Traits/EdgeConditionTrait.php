<?php

namespace Smoren\GraphTools\Conditions\Traits;

use Smoren\GraphTools\Conditions\EdgeCondition;
use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Models\Interfaces\EdgeInterface;

/**
 * @property array<string>|null $edgeTypesOnly
 * @property array<string> $edgeTypesExclude
 */
trait EdgeConditionTrait
{
    /**
     * @return array<string>|null
     */
    public function getEdgeTypesOnly(): ?array
    {
        return $this->edgeTypesOnly;
    }

    /**
     * @return array<string>
     */
    public function getEdgeTypesExclude(): array
    {
        return $this->edgeTypesExclude;
    }

    /**
     * @param array<string>|null $types
     * @return self
     */
    public function onlyEdgeTypes(?array $types): self
    {
        $this->edgeTypesOnly = $types;
        return $this;
    }

    /**
     * @param array<string> $types
     * @return self
     */
    public function excludeEdgeTypes(array $types): self
    {
        $this->edgeTypesExclude = $types;
        return $this;
    }

    public function isSuitableEdge(EdgeInterface $edge): bool
    {
        if($this->getEdgeTypesOnly() !== null && !in_array($edge->getType(), $this->edgeTypesOnly)) {
            return false;
        }

        if(in_array($edge->getType(), $this->edgeTypesExclude)) {
            return false;
        }

        return true;
    }
}
