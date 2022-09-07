<?php

namespace Smoren\GraphTools\Structs\Interfaces;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;

/**
 * Traverse context interface
 * @author Smoren <ofigate@gmail.com>
 */
interface TraverseContextInterface
{
    /**
     * Returns current vertex
     * @return VertexInterface
     */
    public function getVertex(): VertexInterface;

    /**
     * Returns previous vertex
     * @return VertexInterface|null
     */
    public function getPrevVertex(): ?VertexInterface;

    /**
     * Returns current edge
     * @return EdgeInterface|null
     */
    public function getEdge(): ?EdgeInterface;

    /**
     * Returns graph repository
     * @return GraphRepositoryInterface
     */
    public function getRepository(): GraphRepositoryInterface;

    /**
     * Returns branch context
     * @return TraverseBranchContextInterface
     */
    public function getBranchContext(): TraverseBranchContextInterface;

    /**
     * Returns passed vertexes map
     * @return array<string, VertexInterface>
     */
    public function getPassedVertexesMap(): array;

    /**
     * Returns passed vertexes map of all the branches
     * @return array<string, VertexInterface>
     */
    public function getGlobalPassedVertexesMap(): array;

    /**
     * Returns true if loop detected
     * @return bool
     */
    public function isLoop(): bool;
}
