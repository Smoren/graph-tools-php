<?php

namespace Smoren\GraphTools\Components;

use Smoren\GraphTools\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Interfaces\TraverseHandlerInterface;
use Smoren\GraphTools\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\TraverseContext;
use Ds\Set;

class TraverseOld
{
    protected GraphRepositoryInterface $repository;

    public function __construct(GraphRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function runForward(
        VertexInterface $startVertex,
        FilterConditionInterface $filterCondition,
        TraverseHandlerInterface $handler
    ): void
    {
        // TODO вместо нескольких аргументов передавать TraverseContextInterface
        // TODO вместо Logger — TraverseHandler, методы onLoop и onVertex перенести туда
        // TODO внести флаг isLoop в контекст, избавиться от onLoop
        // TODO onVertex должен иметь возможность принимать callback, чтобы не пришлось вводить getData() в context
        // TODO избавиться от рекурсии, принимая array<TraverseContextInterface> и выполняя while(count($contexts))
        // TODO сделать генератором?

        $context = new TraverseContext(
            $startVertex,
            null,
            $filterCondition,
            0,
            false
        );
        $this->_runForward($context, $handler);
    }

    /**
     * @param VertexInterface $startVertex
     * @param Set<string> $branchVertexIdSet
     * @param int $branchIndex
     */
    protected function _runForward(TraverseContextInterface $context, TraverseHandlerInterface $handler): void
    {
        if($branchVertexIdSet->contains($startVertex->getId()) && !$this->onLoop($startVertex, $branchIndex)) {
            return;
        }

        $branchVertexIdSet->add($startVertex->getId());

        if(!$this->onVertex($startVertex, $branchIndex)) {
            return;
        }

        $nextVertexes = $this->repository->getNextVertexes($startVertex);
        foreach($nextVertexes as $i => $vertex) {
            $this->_runForward(
                $vertex,
                count($nextVertexes) === 1 ? $branchVertexIdSet : clone $branchVertexIdSet,
                $i === 0 ? $branchIndex : $branchIndex+1
            );
        }
    }
}
