<?php

namespace Smoren\GraphTools\Filters\Interfaces;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;

/**
 * Filter for graph traversing
 * @author Smoren <ofigate@gmail.com>
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
     * Return true if current vertex should be handled
     * @param TraverseContextInterface $context
     * @return bool
     */
    public function matchesHandleCondition(TraverseContextInterface $context): bool;
}
