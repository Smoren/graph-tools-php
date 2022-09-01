<?php

namespace Smoren\GraphTools\Conditions\Traits;

use Smoren\GraphTools\Conditions\EdgeCondition;
use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Conditions\Interfaces\EdgeConditionInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeInterface;

/**
 * Trait for edge condition implementation
 * @implements EdgeConditionInterface<mixed>
 * @property array<string>|null $edgeTypesOnly edge types whitelist
 * @property array<string> $edgeTypesExclude edge types blacklist
 */
trait EdgeConditionTrait
{
    /**
     * @inheritDoc
     */
    public function getEdgeTypesOnly(): ?array
    {
        return $this->edgeTypesOnly;
    }

    /**
     * @inheritDoc
     */
    public function getEdgeTypesExcluded(): array
    {
        return $this->edgeTypesExclude;
    }

    /**
     * @inheritDoc
     */
    public function onlyEdgeTypes(?array $types): self
    {
        $this->edgeTypesOnly = $types;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function excludeEdgeTypes(array $types): self
    {
        $this->edgeTypesExclude = $types;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isSuitableEdge(EdgeInterface $edge): bool
    {
        if($this->edgeTypesOnly !== null && !in_array($edge->getType(), $this->edgeTypesOnly)) {
            return false;
        }

        if(in_array($edge->getType(), $this->edgeTypesExclude)) {
            return false;
        }

        return true;
    }
}
