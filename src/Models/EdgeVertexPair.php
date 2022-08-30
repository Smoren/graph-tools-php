<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeVertexPairInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * Pair of Vertex and Edge (if given) class
 * @author <ofigate@gmail.com> Smoren
 */
class EdgeVertexPair implements EdgeVertexPairInterface
{
    /**
     * @var EdgeInterface|null edge or null
     */
    protected ?EdgeInterface $edge;
    /**
     * @var VertexInterface vertex
     */
    protected VertexInterface $vertex;

    /**
     * EdgeVertexPair constructor
     * @param EdgeInterface|null $edge edge or null
     * @param VertexInterface $vertex vertex
     */
    public function __construct(?EdgeInterface $edge, VertexInterface $vertex)
    {
        $this->edge = $edge;
        $this->vertex = $vertex;
    }

    /**
     * @inheritDoc
     */
    public function getEdge(): ?EdgeInterface
    {
        return $this->edge;
    }

    /**
     * @inheritDoc
     */
    public function getVertex(): VertexInterface
    {
        return $this->vertex;
    }
}
