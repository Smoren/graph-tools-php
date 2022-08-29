<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeVertexPairInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

class EdgeVertexPair implements EdgeVertexPairInterface
{
    protected VertexInterface $vertex;
    protected ?EdgeInterface $edge;

    public function __construct(VertexInterface $vertex, ?EdgeInterface $edge)
    {
        $this->vertex = $vertex;
        $this->edge = $edge;
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
