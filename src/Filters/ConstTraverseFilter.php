<?php

namespace Smoren\GraphTools\Filters;

use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Conditions\VertexCondition;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Structs\FilterConfig;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;

class ConstTraverseFilter implements TraverseFilterInterface
{
    /**
     * @var FilterConditionInterface|FilterCondition
     */
    protected FilterConditionInterface $passCondition;
    /**
     * @var VertexConditionInterface|VertexCondition
     */
    protected VertexConditionInterface $handleCondition;
    /**
     * @var FilterConfig
     */
    protected FilterConfig $config;

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
            $context->isLoop()
            && $this->config->isOn(FilterConfig::PREVENT_LOOP_PASS)
        ) {
            $passCondition = (clone $this->passCondition)->onlyVertexTypes([]);
        } elseif(
            ($prevVertex = $context->getPrevVertex()) !== null
            && $this->config->isOn(FilterConfig::PREVENT_RETURN_BACK)
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

        if(
            $context->isLoop()
            && $this->config->isOn(FilterConfig::PREVENT_LOOP_HANDLE)
        ) {
            $handleCondition = (clone $this->handleCondition)->onlyVertexTypes([]);
        }

        return $handleCondition;
    }
}
