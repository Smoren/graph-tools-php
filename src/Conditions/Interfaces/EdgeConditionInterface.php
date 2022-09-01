<?php

namespace Smoren\GraphTools\Conditions\Interfaces;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;

/**
 * Interface for edge condition
 * @author <ofigate@gmail.com> Smoren
 */
interface EdgeConditionInterface
{
    /**
     * Returns whitelist of edge types or null if whitelist is not defined
     * @return array<string>|null
     */
    public function getEdgeTypesOnly(): ?array;

    /**
     * Returns blacklist of edge types
     * @return array<string>
     */
    public function getEdgeTypesExclude(): array;

    /**
     * Sets whitelist of edge types
     * @param array<string>|null $types whitelist (null if whitelist is not defined)
     * @return EdgeConditionInterface
     */
    public function onlyEdgeTypes(?array $types): EdgeConditionInterface;

    /**
     * Sets blacklist of edge types
     * @param array<string> $types blacklist
     * @return EdgeConditionInterface
     */
    public function excludeEdgeTypes(array $types): EdgeConditionInterface;

    /**
     * Returns true if edge satisfies the condition
     * @param EdgeInterface $edge edge to check
     * @return bool
     */
    public function isSuitableEdge(EdgeInterface $edge): bool;
}
