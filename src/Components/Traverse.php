<?php

namespace Smoren\GraphTools\Components;

use Ds\Queue;
use Generator;
use Smoren\GraphTools\Components\Interfaces\TraverseInterface;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Models\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Models\TraverseContext;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;

abstract class Traverse implements TraverseInterface
{
    protected GraphRepositoryInterface $repository;

    public function __construct(GraphRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param VertexInterface $start
     * @param TraverseFilterInterface $filter
     * @return Generator<TraverseContextInterface>
     */
    public function generate(VertexInterface $start, TraverseFilterInterface $filter): Generator
    {
        $context = new TraverseContext($start, 0, []);
        yield from $this->traverse(new Queue([$context]), $filter);
    }

    /**
     * @param Queue<TraverseContextInterface> $contexts
     * @param TraverseFilterInterface $filter
     * @return Generator
     */
    protected function traverse(Queue $contexts, TraverseFilterInterface $filter): Generator
    {
        while(count($contexts)) {
            $currentContext = $contexts->pop();
            $currentVertex = $currentContext->getVertex();

            if($filter->getHandleCondition($currentContext)->isSuitableVertex($currentContext->getVertex())) {
                yield $currentContext;
            }

            $passedVertexesMap = $currentContext->getPassedVertexesMap();
            $passedVertexesMap[$currentVertex->getId()] = $currentVertex;

            $nextVertexes = $this->getNextVertexes($currentVertex, $filter->getPassCondition($currentContext));
            foreach($nextVertexes as $i => $vertex) {
                $branchIndex = $currentContext->getBranchIndex();
                $contexts->push(new TraverseContext(
                    $vertex,
                    count($nextVertexes) > 1 && $i > 0 ? $branchIndex+1 : $branchIndex,
                    $passedVertexesMap
                ));
            }
        }
    }

    /**
     * @param VertexInterface $vertex
     * @param FilterConditionInterface $condition
     * @return array<VertexInterface>
     */
    protected function getNextVertexes(VertexInterface $vertex, FilterConditionInterface $condition): array
    {
        return [
            ...$this->repository->getNextVertexes($vertex, $condition),
            ...$this->repository->getPrevVertexes($vertex, $condition),
        ];
    }
}
