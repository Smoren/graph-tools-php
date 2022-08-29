<?php

namespace Smoren\GraphTools\Models\Interfaces;

interface TraverseContextInterface
{
    /**
     * @return VertexInterface
     */
    public function getVertex(): VertexInterface;

    /**
     * @return VertexInterface|null
     */
    public function getPrevVertex(): ?VertexInterface;

    /**
     * @return TraverseBranchContextInterface
     */
    public function getBranchContext(): TraverseBranchContextInterface;

    /**
     * @return array<string, VertexInterface>
     */
    public function getPassedVertexesMap(): array;

    /**
     * @return bool
     */
    public function isLoop(): bool;
}
