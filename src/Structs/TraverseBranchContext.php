<?php

namespace Smoren\GraphTools\Structs;

use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseBranchContextInterface;

/**
 * Traverse branch context implementation
 * @author Smoren <ofigate@gmail.com>
 */
class TraverseBranchContext implements TraverseBranchContextInterface
{
    /**
     * @var int current branch index
     */
    protected int $index;
    /**
     * @var int|null parent branch index
     */
    protected ?int $parentIndex;
    /**
     * @var VertexInterface vertex instance which started current branch
     */
    protected VertexInterface $start;

    /**
     * TraverseBranchContext constructor
     * @param int $index current branch index
     * @param int|null $parentIndex parent branch index
     * @param VertexInterface $start vertex instance which started current branch
     */
    public function __construct(
        int $index,
        ?int $parentIndex,
        VertexInterface $start
    ) {
        $this->index = $index;
        $this->parentIndex = $parentIndex;
        $this->start = $start;
    }

    /**
     * @inheritDoc
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @inheritDoc
     */
    public function getParentIndex(): ?int
    {
        return $this->parentIndex;
    }

    /**
     * @inheritDoc
     */
    public function getStart(): VertexInterface
    {
        return $this->start;
    }
}
