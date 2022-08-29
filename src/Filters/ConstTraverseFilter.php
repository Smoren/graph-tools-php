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
    protected bool $preventLoopContinue;
    protected bool $preventReturnBack;

    public function __construct(
        ?FilterConditionInterface $passCondition = null,
        ?VertexConditionInterface $handleCondition = null,
        bool $preventLoopContinue = true,
        bool $preventReturnBack = false
    ) {
        $this->passCondition = $passCondition ?? new FilterCondition();
        $this->handleCondition = $handleCondition ?? new VertexCondition();
        $this->preventLoopContinue = $preventLoopContinue;
        $this->preventReturnBack = $preventReturnBack;
    }

    /**
     * @inheritDoc
     */
    public function getPassCondition(TraverseContextInterface $context): FilterConditionInterface
    {
        $passCondition = $this->passCondition;

        if($this->preventLoopContinue && $context->isLoop()) {
            $passCondition = (clone $this->passCondition)->onlyVertexTypes([]);
        } elseif($this->preventReturnBack && ($prevVertex = $context->getPrevVertex()) !== null) {
            $passCondition = (clone $this->passCondition)->excludeVertexIds([$prevVertex->getId()]);
        }

        /** @var FilterConditionInterface $passCondition */
        return $passCondition;
    }

    /**
     * @inheritDoc
     */
    public function getHandleCondition(TraverseContextInterface $context): VertexConditionInterface
    {
        return $this->handleCondition;
    }
}
