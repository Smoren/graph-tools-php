<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeVertexPairInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

class EdgeVertexPair implements EdgeVertexPairInterface
{
    protected ?EdgeInterface $edge;
    protected VertexInterface $vertex;

    public function __construct(?EdgeInterface $edge, VertexInterface $vertex)
    {
        $this->edge = $edge;
        $this->vertex = $vertex;
    }

    public function getEdge(): ?EdgeInterface
    {
        return $this->edge;
    }

    public function getVertex(): VertexInterface
    {
        return $this->vertex;
    }
}
