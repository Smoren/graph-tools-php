<?php

namespace Smoren\GraphTools\Components;

use Generator;
use Smoren\GraphTools\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\TraverseContext;

abstract class Traverse
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
        yield from $this->traverse([$context], $filter);
    }

    /**
     * @param array<TraverseContextInterface> $contexts
     * @param TraverseFilterInterface $filter
     * @return Generator
     */
    protected function traverse(array $contexts, TraverseFilterInterface $filter): Generator
    {
        while(count($contexts)) {
            $currentContext = array_pop($contexts);
            $currentVertex = $currentContext->getVertex();

            if($filter->getHandleCondition($currentContext)->isSuitableVertex($currentContext->getVertex())) {
                yield $currentContext;
            }

            $passedVertexesMap = $currentContext->getPassedVertexesMap();
            $passedVertexesMap[$currentVertex->getId()] = $currentVertex;

            $nextVertexes = $this->getNextVertexes($currentVertex, $filter->getPassCondition($currentContext));
            foreach($nextVertexes as $i => $vertex) {
                $branchIndex = $currentContext->getBranchIndex();
                $contexts[] = new TraverseContext(
                    $vertex,
                    count($nextVertexes) > 1 && $i > 0 ? $branchIndex+1 : $branchIndex,
                    $passedVertexesMap
                );
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
