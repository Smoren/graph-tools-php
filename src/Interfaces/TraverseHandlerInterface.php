<?php

namespace Smoren\GraphTools\Interfaces;

use Smoren\GraphTools\Exceptions\TraverseException;

interface TraverseHandlerInterface
{
    /**
     * @param TraverseContextInterface $context
     * @return FilterConditionInterface
     * @throws TraverseException
     */
    public function handle(TraverseContextInterface $context): FilterConditionInterface;
}
