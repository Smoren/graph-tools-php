<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

class TraverseContext implements TraverseContextInterface
{
    /**
     * @var VertexInterface
     */
    protected VertexInterface $vertex;
    /**
     * @var int
     */
    protected int $branchIndex;
    /**
     * @var array<string, VertexInterface>
     */
    protected array $passedVertexesMap;

    /**
     * @param VertexInterface $vertex
     * @param int $branchIndex
     * @param array<string, VertexInterface> $passedVertexesMap
     */
    public function __construct(
        VertexInterface $vertex,
        int $branchIndex,
        array $passedVertexesMap
    ) {
        $this->vertex = $vertex;
        $this->branchIndex = $branchIndex;
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
    public function getBranchIndex(): int
    {
        return $this->branchIndex;
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
