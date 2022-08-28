<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\TraverseBranchContextInterface;
use Smoren\GraphTools\Models\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

class TraverseContext implements TraverseContextInterface
{
    /**
     * @var VertexInterface
     */
    protected VertexInterface $vertex;
    /**
     * @var TraverseBranchContextInterface
     */
    protected TraverseBranchContextInterface $branchContext;
    /**
     * @var array<string, VertexInterface>
     */
    protected array $passedVertexesMap;

    /**
     * @param VertexInterface $vertex
     * @param TraverseBranchContextInterface $branchContext
     * @param array<string, VertexInterface> $passedVertexesMap
     */
    public function __construct(
        VertexInterface $vertex,
        TraverseBranchContextInterface $branchContext,
        array $passedVertexesMap
    ) {
        $this->vertex = $vertex;
        $this->branchContext = $branchContext;
        $this->passedVertexesMap = $passedVertexesMap;
    }

    /**
     * @inheritDoc
     */
    public function getVertex(): VertexInterface
    {
        return $this->vertex;
    }

    /**
     * @inheritDoc
     */
    public function getBranchContext(): TraverseBranchContextInterface
    {
        return $this->branchContext;
    }

    /**
     * @inheritDoc
     */
    public function getPassedVertexesMap(): array
    {
        return $this->passedVertexesMap;
    }

    /**
     * @inheritDoc
     */
    public function isLoop(): bool
    {
        return isset($this->passedVertexesMap[$this->vertex->getId()]);
    }
}
