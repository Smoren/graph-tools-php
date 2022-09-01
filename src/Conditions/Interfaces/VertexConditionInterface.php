<?php

namespace Smoren\GraphTools\Conditions\Interfaces;

use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * Interface for vertex condition
 * @author <ofigate@gmail.com> Smoren
 */
interface VertexConditionInterface
{
    /**
     * Returns whitelist of vertex types or null if whitelist is not defined
     * @return array<string>|null
     */
    public function getVertexTypesOnly(): ?array;

    /**
     * Returns blacklist of vertex types
     * @return array<string>
     */
    public function getVertexTypesExcluded(): array;

    /**
     * Returns whitelist of vertex ids or null if whitelist is not defined
     * @return array<string>|null
     */
    public function getVertexIdsOnly(): ?array;

    /**
     * Returns blacklist of vertex ids
     * @return array<string>
     */
    public function getVertexIdsExcluded(): array;

    /**
     * Sets whitelist of vertex types
     * @param array<string>|null $types whitelist (null if whitelist is not defined)
     * @return VertexConditionInterface
     */
    public function onlyVertexTypes(?array $types): VertexConditionInterface;

    /**
     * Sets blacklist of vertex types
     * @param array<string> $types blacklist
     * @return VertexConditionInterface
     */
    public function excludeVertexTypes(array $types): VertexConditionInterface;

    /**
     * Sets whitelist of vertex ids
     * @param array<string>|null $ids whitelist (null if whitelist is not defined)
     * @return VertexConditionInterface
     */
    public function onlyVertexIds(?array $ids): VertexConditionInterface;

    /**
     * Sets blacklist of vertex ids
     * @param array<string> $ids blacklist
     * @return VertexConditionInterface
     */
    public function excludeVertexIds(array $ids): VertexConditionInterface;

    /**
     * Returns true if vertex satisfies the condition
     * @param VertexInterface $vertex vertex to check
     * @return bool
     */
    public function isSuitableVertex(VertexInterface $vertex): bool;
}
