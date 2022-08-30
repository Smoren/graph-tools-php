<?php

namespace Smoren\GraphTools\Components;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseStepIteratorInterface;

/**
 * Class for reverse traversing of the directional graph
 * @author <ofigate@gmail.com> Smoren
 */
class TraverseReverse extends Traverse
{
    /**
     * @inheritDoc
     */
    protected function getNextVertexes(
        VertexInterface $vertex,
        FilterConditionInterface $condition
    ): TraverseStepIteratorInterface {
        return $this->repository->getPrevVertexes($vertex, $condition);
    }
}
