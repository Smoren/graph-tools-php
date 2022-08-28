<?php

namespace Smoren\GraphTools\Filters;

use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Conditions\VertexCondition;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Models\Interfaces\TraverseContextInterface;

class ConstTraverseFilter implements TraverseFilterInterface
{
    protected FilterConditionInterface $passCondition;
    protected VertexConditionInterface $handleCondition;
    protected bool $preventLoop;

    public function __construct(
        ?FilterConditionInterface $passCondition = null,
        ?VertexConditionInterface $handleCondition = null,
        bool $preventLoop = true
    ) {
        $this->passCondition = $passCondition ?? new FilterCondition();
        $this->handleCondition = $handleCondition ?? new VertexCondition();
        $this->preventLoop = $preventLoop;
    }

    /**
     * @inheritDoc
     */
    public function getPassCondition(TraverseContextInterface $context): FilterConditionInterface
    {
        if($context->isLoop()) {
            return (new FilterCondition())->setVertexTypesOnly([]);
        }
        return $this->passCondition;
    }

    /**
     * @inheritDoc
     */
    public function getHandleCondition(TraverseContextInterface $context): VertexConditionInterface
    {
        return $this->handleCondition;
    }
}
