<?php

namespace Smoren\GraphTools\Filters\Interfaces;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;

/**
 * Filter for graph traversing
 * @author <ofigate@gmail.com> Smoren
 */
interface TraverseFilterInterface
{
    /**
     * Returns condition: pass or not pass current vertex (stop after current vertex)
     * @param TraverseContextInterface $context traverse context
     * @return FilterConditionInterface condition of next behavior
     */
    public function getPassCondition(TraverseContextInterface $context): FilterConditionInterface;

    /**
     * Returns condition: handle or not handle current vertex (yield or ignore current vertex)
     * @param TraverseContextInterface $context
     * @return VertexConditionInterface condition of handle behavior
     */
    public function getHandleCondition(TraverseContextInterface $context): VertexConditionInterface;
}
