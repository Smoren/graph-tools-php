<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

class TraverseContext implements TraverseContextInterface
{
    protected VertexInterface $vertex;
    protected int $branchIndex;
    /**
     * @var array<string, VertexInterface>
     */
    protected array $passedVertexesMap;

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
     * @return VertexInterface
     */
    public function getVertex(): VertexInterface
    {
        return $this->vertex;
    }

    /**
     * @return int
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
     * @return bool
     */
    public function isLoop(): bool
    {
        return isset($this->passedVertexesMap[$this->vertex->getId()]);
    }
}
