<?php

namespace Smoren\GraphTools\Conditions\Interfaces;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;

interface EdgeConditionInterface
{
    /**
     * @return array<string>|null
     */
    public function getEdgeTypesOnly(): ?array;

    /**
     * @return array<string>
     */
    public function getEdgeTypesExclude(): array;

    /**
     * @param array<string>|null $types
     * @return EdgeConditionInterface
     */
    public function onlyEdgeTypes(?array $types): EdgeConditionInterface;

    /**
     * @param array<string> $types
     * @return EdgeConditionInterface
     */
    public function excludeEdgeTypes(array $types): EdgeConditionInterface;

    /**
     * @param EdgeInterface $edge
     * @return bool
     */
    public function isSuitableEdge(EdgeInterface $edge): bool;
}
