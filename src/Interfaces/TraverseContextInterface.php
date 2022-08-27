<?php

namespace Smoren\GraphTools\Interfaces;

interface TraverseContextInterface
{
    /**
     * @return VertexInterface
     */
    public function getVertex(): VertexInterface;

    /**
     * @return int
     */
    public function getBranchIndex(): int;

    /**
     * @return array<string, VertexInterface>
     */
    public function getPassedVertexesMap(): array;

    /**
     * @return bool
     */
    public function isLoop(): bool;
}
