<?php

namespace Smoren\GraphTools\Tests\Unit\Filters;

use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Conditions\VertexCondition;
use Smoren\GraphTools\Filters\ConfigurableTraverseFilter;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Tests\Unit\Models\OperatorAndVertex;
use Smoren\GraphTools\Tests\Unit\Structs\VertexType;

class WorkflowHiddenBranchingTraverseFilter extends ConfigurableTraverseFilter
{
    /**
     * @var array<string, int>
     */
    protected array $operatorAndPassCounterMap = [];

    public function matchesHandleCondition(TraverseContextInterface $context): bool
    {
        $vertex = $context->getVertex();
        if($vertex instanceof OperatorAndVertex) {
            $this->incrementOperatorHandleCount($vertex->getId());
        }
        return parent::matchesHandleCondition($context);
    }

    protected function getDefaultPassCondition(TraverseContextInterface $context): FilterConditionInterface
    {
        $vertex = $context->getVertex();

        if($vertex instanceof OperatorAndVertex) {
            if(!$this->canPassOperatorAnd($vertex, $context->getRepository())) {
                return (new FilterCondition())->onlyVertexTypes([]);
            }
        }

        return new FilterCondition();
    }

    protected function getDefaultHandleCondition(TraverseContextInterface $context): VertexConditionInterface
    {
        return (new VertexCondition())->onlyVertexTypes([VertexType::EVENT, VertexType::FUNCTION]);
    }

    protected function incrementOperatorHandleCount(string $operatorId): void
    {
        if(!isset($this->operatorAndPassCounterMap[$operatorId])) {
            $this->operatorAndPassCounterMap[$operatorId] = 0;
        }
        $this->operatorAndPassCounterMap[$operatorId]++;
    }

    protected function canPassOperatorAnd(OperatorAndVertex $operator, GraphRepositoryInterface $repo): bool
    {
        return ($this->operatorAndPassCounterMap[$operator->getId()] ?? 0) === $operator->countInput($repo);
    }
}
