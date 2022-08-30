<?php

namespace Smoren\GraphTools\Structs;

use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseBranchContextInterface;

class TraverseBranchContext implements TraverseBranchContextInterface
{
    /**
     * @var int
     */
    protected int $index;
    /**
     * @var int|null
     */
    protected ?int $parentIndex;
    /**
     * @var VertexInterface
     */
    protected VertexInterface $start;

    /**
     * @param int $index
     * @param int|null $parentIndex
     * @param VertexInterface $start
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
