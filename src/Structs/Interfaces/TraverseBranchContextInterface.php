<?php

namespace Smoren\GraphTools\Structs\Interfaces;

use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * Traverse branch context interface
 * @author Smoren <ofigate@gmail.com>
 */
interface TraverseBranchContextInterface
{
    /**
     * Returns index of the current branch
     * @return int
     */
    public function getIndex(): int;

    /**
     * Returns index of the parent branch
     * @return int|null
     */
    public function getParentIndex(): ?int;

    /**
     * Returns vertex instance which started current branch
     * @return VertexInterface
     */
    public function getStart(): VertexInterface;
}
