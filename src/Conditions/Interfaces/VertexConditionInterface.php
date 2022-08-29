<?php

namespace Smoren\GraphTools\Conditions\Interfaces;

use Smoren\GraphTools\Models\Interfaces\VertexInterface;

interface VertexConditionInterface
{
    /**
     * @return array<string>|null
     */
    public function getVertexTypesOnly(): ?array;

    /**
     * @return array<string>
     */
    public function getVertexTypesExclude(): array;

    /**
     * @return array<string>|null
     */
    public function getVertexIdsOnly(): ?array;

    /**
     * @return array<string>
     */
    public function getVertexIdsExclude(): array;

    /**
     * @param array<string>|null $types
     * @return VertexConditionInterface
     */
    public function onlyVertexTypes(?array $types): VertexConditionInterface;

    /**
     * @param array<string> $types
     * @return VertexConditionInterface
     */
    public function excludeVertexTypes(array $types): VertexConditionInterface;

    /**
     * @param array<string>|null $ids
     * @return VertexConditionInterface
     */
    public function onlyVertexIds(?array $ids): VertexConditionInterface;

    /**
     * @param array<string> $ids
     * @return VertexConditionInterface
     */
    public function excludeVertexIds(array $ids): VertexConditionInterface;

    /**
     * @param VertexInterface $vertex
     * @return bool
     */
    public function isSuitableVertex(VertexInterface $vertex): bool;
}
