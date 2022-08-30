<?php

namespace Smoren\GraphTools\Structs\Interfaces;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

interface TraverseContextInterface
{
    /**
     * @return VertexInterface
     */
    public function getVertex(): VertexInterface;

    /**
     * @return VertexInterface|null
     */
    public function getPrevVertex(): ?VertexInterface;

    /**
     * @return EdgeInterface|null
     */
    public function getEdge(): ?EdgeInterface;

    /**
     * @return TraverseBranchContextInterface
     */
    public function getBranchContext(): TraverseBranchContextInterface;

    /**
     * @return array<string, VertexInterface>
     */
    public function getPassedVertexesMap(): array;

    /**
     * @return array<string, VertexInterface>
     */
    public function getGlobalPassedVertexesMap(): array;

    /**
     * @return bool
     */
    public function isLoop(): bool;
}
