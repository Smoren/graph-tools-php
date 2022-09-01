<?php

namespace Smoren\GraphTools\Filters;

use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Conditions\VertexCondition;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Structs\FilterConfig;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;

/**
 * Constant traverse filter
 * @author <ofigate@gmail.com> Smoren
 */
class ConstTraverseFilter extends BaseTraverseFilter implements TraverseFilterInterface
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
     * @var FilterConfig filter config
     */
    protected FilterConfig $config;

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
        $this->passCondition = $passCondition ?? new FilterCondition();
        $this->handleCondition = $handleCondition ?? new VertexCondition();
        $this->config = new FilterConfig($config);
    }

    protected function _getPassCondition(): FilterCondition
    {
        return $this->passCondition;
    }

    protected function _getHandleCondition(): VertexCondition
    {
        return $this->handleCondition;
    }
}
