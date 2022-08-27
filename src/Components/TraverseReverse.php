<?php

namespace Smoren\GraphTools\Components;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

class TraverseReverse extends Traverse
{
    /**
     * @inheritDoc
     */
    protected function getNextVertexes(VertexInterface $vertex, FilterConditionInterface $condition): array
    {
        return $this->repository->getPrevVertexes($vertex, $condition);
    }
}
