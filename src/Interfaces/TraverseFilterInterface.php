<?php

namespace Smoren\GraphTools\Interfaces;

use Smoren\GraphTools\Exceptions\TraverseException;

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
