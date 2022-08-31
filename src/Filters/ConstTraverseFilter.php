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
class ConstTraverseFilter implements TraverseFilterInterface
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

    /**
     * @inheritDoc
     */
    public function getPassCondition(TraverseContextInterface $context): FilterConditionInterface
    {
        $passCondition = $this->passCondition;

        if(
            $this->config->has(FilterConfig::PREVENT_LOOP_PASS)
            && $context->isLoop()
        ) {
            $passCondition = (clone $this->passCondition)->onlyVertexTypes([]);
        } elseif(
            $this->config->has(FilterConfig::PREVENT_RETURN_BACK)
            && ($prevVertex = $context->getPrevVertex()) !== null
        ) {
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
        $handleCondition = $this->handleCondition;
        $globalPassed = $context->getGlobalPassedVertexesMap();

        if(
            (
                $this->config->has(FilterConfig::PREVENT_LOOP_HANDLE)
                && $context->isLoop()
            ) || (
                $this->config->has(FilterConfig::HANDLE_UNIQUE_VERTEXES)
                && isset($globalPassed[$context->getVertex()->getId()])
            )
        ) {
            $handleCondition = (clone $this->handleCondition)->onlyVertexTypes([]);
        }

        return $handleCondition;
    }
}
