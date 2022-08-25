<?php

namespace Smoren\GraphTools\Components;

use Smoren\GraphTools\Interfaces\GraphRepositoryInterface;
use Ds\Set;
use Smoren\GraphTools\Interfaces\VertexInterface;

class GraphTraverse
{
    protected GraphRepositoryInterface $repository;
    protected Logger $logger;

    public function __construct(GraphRepositoryInterface $repository, ?Logger $logger = null)
    {
        $this->repository = $repository;
        $this->logger = $logger ?? new Logger();
    }

    public function runForward(VertexInterface $startVertex): void
    {
        // TODO вместо нескольких аргументов передавать TraverseContextInterface
        // TODO вместо Logger — TraverseHandler, методы onLoop и onVertex перенести туда
        // TODO избавиться от рекурсии, принимая array<TraverseContextInterface> и выполняя while(count($contexts))
        $this->_runForward($startVertex, new Set(), 0);
    }

    /**
     * @return array<string>
     */
    public function getLog(): array
    {
        return $this->logger->get();
    }

    protected function onLoop(VertexInterface $vertex, int $branchIndex): bool
    {
        $this->logger->log("[BRANCH {$branchIndex}] [LOOP] {$vertex->getId()}");
        return false;
    }

    protected function onVertex(VertexInterface $vertex, int $branchIndex): bool
    {
        $this->logger->log("[BRANCH {$branchIndex}] [VERTEX] {$vertex->getId()}");
        return true;
    }

    /**
     * @param VertexInterface $startVertex
     * @param Set<string> $branchVertexIdSet
     * @param int $branchIndex
     */
    protected function _runForward(VertexInterface $startVertex, Set $branchVertexIdSet, int $branchIndex): void
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
