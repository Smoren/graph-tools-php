<?php

namespace Smoren\GraphTools\Structs\Interfaces;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * Pair of Vertex and Edge (if given) interface
 * @author <ofigate@gmail.com> Smoren
 */
interface TraverseStepPairInterface
{
    /**
     * Returns edge object from pair if it exists
     * @return EdgeInterface|null
     */
    public function getEdge(): ?EdgeInterface;
    /**
     * Returns vertex object from pair
     * @return VertexInterface
     */
    public function getVertex(): VertexInterface;
}
