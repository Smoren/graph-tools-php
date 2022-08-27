<?php

namespace Smoren\GraphTools\Filters\Interfaces;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Models\Interfaces\TraverseContextInterface;

interface TraverseFilterInterface
{
    /**
     * @param TraverseContextInterface $context
     * @return FilterConditionInterface
     */
    public function getPassCondition(TraverseContextInterface $context): FilterConditionInterface;

    /**
     * @param TraverseContextInterface $context
     * @return VertexConditionInterface
     */
    public function getHandleCondition(TraverseContextInterface $context): VertexConditionInterface;
}
