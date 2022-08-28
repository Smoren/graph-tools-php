<?php

namespace Smoren\GraphTools\Models\Interfaces;

interface TraverseBranchContextInterface
{
    /**
     * @return int
     */
    public function getIndex(): int;

    /**
     * @return int|null
     */
    public function getParentIndex(): ?int;

    /**
     * @return VertexInterface
     */
    public function getStart(): VertexInterface;
}
