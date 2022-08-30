<?php

namespace Smoren\GraphTools\Models\Interfaces;

/**
 * Pair of Vertex and Edge (if given) interface
 * @author <ofigate@gmail.com> Smoren
 */
interface EdgeVertexPairInterface
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
