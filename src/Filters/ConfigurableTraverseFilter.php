<?php

namespace Smoren\GraphTools\Filters;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Structs\FilterConfig;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;

abstract class ConfigurableTraverseFilter implements TraverseFilterInterface
{
    /**
     * @var FilterConfig filter config
     */
    protected FilterConfig $config;

    /**
     * ConfigurableTraverseFilter constructor
     * @param array<int> $config
     */
    public function __construct(array $config)
    {
        $this->config = new FilterConfig($config);
    }

    /**
     * @inheritDoc
     */
    public function getPassCondition(TraverseContextInterface $context): FilterConditionInterface
    {
        $passCondition = $this->getDefaultPassCondition($context);

        if($this->needToPreventLoopPass($context)) {
            $passCondition = (clone $passCondition)->onlyVertexTypes([]);
        } elseif($this->needToCheckReturnBackPass() && ($prevVertex = $context->getPrevVertex()) !== null) {
            $passCondition = (clone $passCondition)->excludeVertexIds([$prevVertex->getId()]);
        }

        /** @var FilterConditionInterface $passCondition */
        return $passCondition;
    }

    /**
     * @inheritDoc
     */
    public function matchesHandleCondition(TraverseContextInterface $context): bool
    {
        $handleCondition = $this->getDefaultHandleCondition($context);

        if($this->needToPreventLoopHandle($context) || $this->needToPreventRepeatHandle($context)) {
            $handleCondition = (clone $handleCondition)->onlyVertexTypes([]);
        }

        return $handleCondition->isSuitableVertex($context->getVertex());
    }

    /**
     * Returns the default pass condition
     * @param TraverseContextInterface $context traverse context
     * @return FilterConditionInterface
     */
    abstract protected function getDefaultPassCondition(TraverseContextInterface $context): FilterConditionInterface;

    /**
     * Returns the default handle condition
     * @param TraverseContextInterface $context traverse context
     * @return VertexConditionInterface
     */
    abstract protected function getDefaultHandleCondition(TraverseContextInterface $context): VertexConditionInterface;

    /**
     * Returns true if we need to prevent loop pass
     * @param TraverseContextInterface $context traverse context
     * @return bool
     */
    protected function needToPreventLoopPass(TraverseContextInterface $context): bool
    {
        return $this->config->has(FilterConfig::PREVENT_LOOP_PASS)
            && $context->isLoop();
    }

    /**
     * Returns true if we need to prevent return back pass
     * @return bool
     */
    protected function needToCheckReturnBackPass(): bool
    {
        return $this->config->has(FilterConfig::PREVENT_RETURN_BACK_PASS);
    }

    /**
     * Returns true if we need to prevent loop handle
     * @param TraverseContextInterface $context traverse context
     * @return bool
     */
    protected function needToPreventLoopHandle(TraverseContextInterface $context): bool
    {
        return $this->config->has(FilterConfig::PREVENT_LOOP_HANDLE)
            && $context->isLoop();
    }

    /**
     * Returns true if we need to prevent repeat handle
     * @param TraverseContextInterface $context traverse context
     * @return bool
     */
    protected function needToPreventRepeatHandle(TraverseContextInterface $context): bool
    {
        return $this->config->has(FilterConfig::PREVENT_REPEAT_HANDLE)
            && isset($context->getGlobalPassedVertexesMap()[$context->getVertex()->getId()]);
    }
}
