<?php

namespace Smoren\GraphTools\Structs;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseBranchContextInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;

/**
 * Traverse context implementation
 * @author Smoren <ofigate@gmail.com>
 */
class TraverseContext implements TraverseContextInterface
{
    /**
     * @var VertexInterface current vertex
     */
    protected VertexInterface $vertex;
    /**
     * @var EdgeInterface|null edge which led to current vertex
     */
    protected ?EdgeInterface $edge;
    /**
     * @var TraverseBranchContextInterface current branch context
     */
    protected TraverseBranchContextInterface $branchContext;
    /**
     * @var array<string, VertexInterface> passed vertexes map
     */
    protected array $passedVertexesMap;
    /**
     * @var array<string, VertexInterface> passed vertexes map of all the branches
     */
    protected array $globalPassedVertexesMap;

    /**
     * TraverseContext constructor
     * @param VertexInterface $vertex current vertex
     * @param EdgeInterface|null $edge edge which led to current vertex
     * @param TraverseBranchContextInterface $branchContext current branch context
     * @param array<string, VertexInterface> $passedVertexesMap passed vertexes map
     * @param array<string, VertexInterface> $globalPassedVertexesMap passed vertexes map of all the branches
     */
    public function __construct(
        VertexInterface $vertex,
        ?EdgeInterface $edge,
        TraverseBranchContextInterface $branchContext,
        array $passedVertexesMap,
        array &$globalPassedVertexesMap
    ) {
        $this->vertex = $vertex;
        $this->edge = $edge;
        $this->branchContext = $branchContext;
        $this->passedVertexesMap = $passedVertexesMap;
        $this->globalPassedVertexesMap = &$globalPassedVertexesMap;
    }

    /**
     * @inheritDoc
     */
    public function getVertex(): VertexInterface
    {
        return $this->vertex;
    }

    /**
     * @return VertexInterface|null
     */
    public function getPrevVertex(): ?VertexInterface
    {
        $candidate = end($this->passedVertexesMap);
        return $candidate ?: null;
    }

    /**
     * @return EdgeInterface|null
     */
    public function getEdge(): ?EdgeInterface
    {
        return $this->edge;
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
    public function getGlobalPassedVertexesMap(): array
    {
        return $this->globalPassedVertexesMap;
    }

    /**
     * @inheritDoc
     */
    public function isLoop(): bool
    {
        return isset($this->passedVertexesMap[$this->vertex->getId()]);
    }
}
