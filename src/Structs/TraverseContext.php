<?php

namespace Smoren\GraphTools\Structs;

use Smoren\GraphTools\Interfaces\ConnectionInterface;
use Smoren\GraphTools\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Interfaces\VertexInterface;

class TraverseContext implements TraverseContextInterface
{
    protected VertexInterface $vertex;
    protected ?ConnectionInterface $connection;
    protected FilterConditionInterface $filterCondition;
    protected int $branchIndex;

    public function __construct(
        VertexInterface $vertex,
        ?ConnectionInterface $connection,
        FilterConditionInterface $filterCondition,
        int $branchIndex
    ) {
        $this->vertex = $vertex;
        $this->connection = $connection;
        $this->filterCondition = $filterCondition;
        $this->branchIndex = $branchIndex;
    }

    /**
     * @return VertexInterface
     */
    public function getVertex(): VertexInterface
    {
        return $this->vertex;
    }

    /**
     * @return ConnectionInterface|null
     */
    public function getConnection(): ?ConnectionInterface
    {
        return $this->connection;
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
}
