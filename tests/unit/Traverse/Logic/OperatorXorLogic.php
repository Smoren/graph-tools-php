<?php

namespace Smoren\GraphTools\Tests\Unit\Traverse\Logic;

use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseStepIteratorInterface;
use Smoren\GraphTools\Tests\Unit\Models\EventVertex;
use Smoren\GraphTools\Tests\Unit\Models\FunctionVertex;
use Smoren\GraphTools\Tests\Unit\Models\OperatorXorVertex;
use Smoren\GraphTools\Tests\Unit\Structs\VertexType;

class OperatorXorLogic
{
    protected array $passMap = [];

    public function registerFunction(FunctionVertex $func, GraphRepositoryInterface $repository): self
    {
        $nextVertexes = $repository->getNextVertexes(
            $func,
            (new FilterCondition())->onlyVertexTypes([VertexType::OPERATOR_XOR])
        );
        foreach($nextVertexes as $nextVertex) {
            if($nextVertex instanceof OperatorXorVertex) {
                $this->passMap[$nextVertex->getId()] = $this->getFunctionResult(
                    $func,
                    $repository->getNextVertexes(
                        $nextVertex,
                        (new FilterCondition())->onlyVertexTypes([VertexType::EVENT])
                    )
                );
            }
        }

        return $this;
    }

    public function getPassFilter(OperatorXorVertex $operator): ?FilterConditionInterface
    {
        if(isset($this->passMap[$operator->getId()])) {
            return (new FilterCondition())->onlyVertexIds([$this->passMap[$operator->getId()]]);
        }

        return null;
    }

    protected function getFunctionResult(FunctionVertex $function, TraverseStepIteratorInterface $nextEvents): string
    {
        return min(array_map(function(EventVertex $event) {
            return $event->getId();
        }, iterator_to_array($nextEvents, false)));
    }
}
