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

    /**
     * @param FilterConditionInterface|null $passCondition
     * @param VertexConditionInterface|null $handleCondition
     * @param array<int> $config
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
            $this->config->isOn(FilterConfig::PREVENT_LOOP_PASS)
            && $context->isLoop()
        ) {
            $passCondition = (clone $this->passCondition)->onlyVertexTypes([]);
        } elseif(
            $this->config->isOn(FilterConfig::PREVENT_RETURN_BACK)
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
                $this->config->isOn(FilterConfig::PREVENT_LOOP_HANDLE)
                && $context->isLoop()
            ) || (
                $this->config->isOn(FilterConfig::HANDLE_UNIQUE_VERTEXES)
                && isset($globalPassed[$context->getVertex()->getId()])
            )
        ) {
            $handleCondition = (clone $this->handleCondition)->onlyVertexTypes([]);
        }

        return $handleCondition;
    }
}
