<?php

namespace Smoren\GraphTools\Filters;

use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Conditions\VertexCondition;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Structs\FilterConfig;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;

abstract class BaseTraverseFilter implements TraverseFilterInterface
{
    /**
     * @inheritDoc
     */
    public function getPassCondition(TraverseContextInterface $context): FilterConditionInterface
    {
        $passCondition = $this->_getPassCondition();

        if(
            $this->config->has(FilterConfig::PREVENT_LOOP_PASS)
            && $context->isLoop()
        ) {
            $passCondition = (clone $passCondition)->onlyVertexTypes([]);
        } elseif(
            $this->config->has(FilterConfig::PREVENT_RETURN_BACK_PASS)
            && ($prevVertex = $context->getPrevVertex()) !== null
        ) {
            $passCondition = (clone $passCondition)->excludeVertexIds([$prevVertex->getId()]);
        }

        /** @var FilterConditionInterface $passCondition */
        return $passCondition;
    }

    /**
     * @inheritDoc
     */
    public function getHandleCondition(TraverseContextInterface $context): VertexConditionInterface
    {
        $handleCondition = $this->_getHandleCondition();
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
            $handleCondition = (clone $handleCondition)->onlyVertexTypes([]);
        }

        return $handleCondition;
    }

    abstract protected function _getPassCondition(): FilterCondition;
    abstract protected function _getHandleCondition(): VertexCondition;
}