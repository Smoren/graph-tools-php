<?php

namespace Smoren\GraphTools\Components;

use Generator;
use Smoren\GraphTools\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\TraverseContext;

class TraverseDirect
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

            if($filter->getHandleCondition($currentContext)->isSuitableVertex($currentContext->getVertex())) {
                yield $currentContext;
            }

            $nextVertexes = $this->repository->getNextVertexes(
                $currentContext->getVertex(),
                $filter->getPassCondition($currentContext)
            );
            foreach($nextVertexes as $vertex) {
                $branchIndex = $currentContext->getBranchIndex();
                $contexts[] = new TraverseContext(
                    $vertex,
                    count($nextVertexes) > 1 ? $branchIndex+1 : $branchIndex,
                    $currentContext->getPassedVertexesMap()
                );
            }
        }
    }
}