<?php

namespace Smoren\GraphTools\Components;

use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Conditions\VertexCondition;
use Smoren\GraphTools\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Interfaces\VertexConditionInterface;

class SimpleTraverseFilter implements TraverseFilterInterface
{
    /**
     * @inheritDoc
     */
    public function getPassCondition(TraverseContextInterface $context): FilterConditionInterface
    {
        if($context->isLoop()) {
            return (new FilterCondition())->setVertexTypesOnly([]);
        }
        return new FilterCondition();
    }

    /**
     * @inheritDoc
     */
    public function getHandleCondition(TraverseContextInterface $context): VertexConditionInterface
    {
        return new VertexCondition();
    }
}
