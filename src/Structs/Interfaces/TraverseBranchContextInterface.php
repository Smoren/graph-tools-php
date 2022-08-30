<?php

namespace Smoren\GraphTools\Structs\Interfaces;

use Smoren\GraphTools\Models\Interfaces\VertexInterface;

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
