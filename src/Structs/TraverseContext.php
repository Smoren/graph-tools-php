<?php

namespace Smoren\GraphTools\Structs;

use Smoren\GraphTools\Interfaces\ConnectionInterface;
use Smoren\GraphTools\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Interfaces\VertexInterface;

class TraverseContext implements TraverseContextInterface
{
    protected VertexInterface $vertex;
    protected FilterConditionInterface $filterCondition;
    protected int $branchIndex;
    protected bool $isLoop;

    public function __construct(
        VertexInterface $vertex,
        FilterConditionInterface $filterCondition,
        int $branchIndex,
        bool $isLoop
    ) {
        $this->vertex = $vertex;
        $this->filterCondition = $filterCondition;
        $this->branchIndex = $branchIndex;
        $this->isLoop = $isLoop;
    }

    /**
     * @return VertexInterface
     */
    public function getVertex(): VertexInterface
    {
        return $this->vertex;
    }

    /**
     * @return FilterConditionInterface
     */
    public function getFilterCondition(): FilterConditionInterface
    {
        return $this->filterCondition;
    }

    /**
     * @return int
     */
    public function getBranchIndex(): int
    {
        return $this->branchIndex;
    }

    /**
     * @return bool
     */
    public function getIsLoop(): bool
    {
        return $this->isLoop;
    }
}
