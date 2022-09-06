<?php

namespace Smoren\GraphTools\Filters;

use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Conditions\VertexCondition;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;

/**
 * Constant traverse filter
 * @author Smoren <ofigate@gmail.com>
 */
class ConstTraverseFilter extends ConfigurableTraverseFilter implements TraverseFilterInterface
{
    /**
     * @var FilterConditionInterface|FilterCondition condition of next traverse behavior
     */
    protected FilterConditionInterface $passCondition;
    /**
     * @var VertexConditionInterface|VertexCondition condition of current vertex handling
     */
    protected VertexConditionInterface $handleCondition;

    /**
     * ConstTraverseFilter constructor
     * @param FilterConditionInterface|null $passCondition condition of next traverse behavior
     * @param VertexConditionInterface|null $handleCondition condition of current vertex handling
     * @param array<int> $config filter config
     */
    public function __construct(
        ?FilterConditionInterface $passCondition = null,
        ?VertexConditionInterface $handleCondition = null,
        array $config = []
    ) {
        parent::__construct($config);
        $this->passCondition = $passCondition ?? new FilterCondition();
        $this->handleCondition = $handleCondition ?? new VertexCondition();
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultPassCondition(TraverseContextInterface $context): FilterConditionInterface
    {
        return $this->passCondition;
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultHandleCondition(TraverseContextInterface $context): VertexConditionInterface
    {
        return $this->handleCondition;
    }
}
