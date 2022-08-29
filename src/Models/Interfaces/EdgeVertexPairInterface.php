<?php

namespace Smoren\GraphTools\Models\Interfaces;

interface EdgeVertexPairInterface
{
    public function getEdge(): ?EdgeInterface;
    public function getVertex(): VertexInterface;
}
