<?php

namespace Smoren\GraphTools\Interfaces;

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
     * @param array<string>|null $types
     * @return VertexConditionInterface
     */
    public function setVertexTypesOnly(?array $types): VertexConditionInterface;

    /**
     * @param array<string> $types
     * @return VertexConditionInterface
     */
    public function setVertexTypesExclude(array $types): VertexConditionInterface;

    /**
     * @param VertexInterface $vertex
     * @return bool
     */
    public function isSuitableVertex(VertexInterface $vertex): bool;
}
