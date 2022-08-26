<?php

namespace Smoren\GraphTools\Interfaces;

use Generator;
use Smoren\GraphTools\Exceptions\TraverseException;

interface TraverseHandlerInterface
{
    /**
     * @param TraverseContextInterface $context
     * @return Generator<VertexInterface>
     * @throws TraverseException
     */
    public function handle(TraverseContextInterface $context): Generator;

    public function getFilterCondition(TraverseContextInterface $context): FilterConditionInterface;
}
