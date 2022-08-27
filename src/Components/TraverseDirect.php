<?php

namespace Smoren\GraphTools\Components;

use Smoren\GraphTools\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Interfaces\VertexInterface;

class TraverseDirect extends Traverse
{
    /**
     * @param VertexInterface $vertex
     * @param FilterConditionInterface $condition
     * @return array<VertexInterface>
     */
    protected function getNextVertexes(VertexInterface $vertex, FilterConditionInterface $condition): array
    {
        return $this->repository->getNextVertexes($vertex, $condition);
    }
}