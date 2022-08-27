<?php

namespace Smoren\GraphTools\Interfaces;

interface TraverseContextInterface
{
    public function getVertex(): VertexInterface;
    public function getBranchIndex(): int;
    /**
     * @return array<string, VertexInterface>
     */
    public function getPassedVertexesMap(): array;
    public function isLoop(): bool;
}
