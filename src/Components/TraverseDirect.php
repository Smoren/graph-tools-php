<?php

namespace Smoren\GraphTools\Components;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeVertexPairsIteratorInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

class TraverseDirect extends Traverse
{
    /**
     * @inheritDoc
     */
    protected function getNextVertexes(
        VertexInterface $vertex,
        FilterConditionInterface $condition
    ): EdgeVertexPairsIteratorInterface {
        return $this->repository->getNextVertexes($vertex, $condition);
    }
}
