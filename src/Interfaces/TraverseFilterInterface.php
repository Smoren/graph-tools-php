<?php

namespace Smoren\GraphTools\Interfaces;

interface TraverseFilterInterface
{
    public function getPassCondition(TraverseContextInterface $context): FilterConditionInterface;
    public function getHandleCondition(TraverseContextInterface $context): VertexConditionInterface;
}
