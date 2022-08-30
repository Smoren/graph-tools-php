<?php

namespace Smoren\GraphTools\Components;

use Ds\Queue;
use Generator;
use Smoren\GraphTools\Components\Interfaces\TraverseInterface;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Models\EdgeVertexPairsIterator;
use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeVertexPairsIteratorInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseBranchContextInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Structs\TraverseBranchContext;
use Smoren\GraphTools\Structs\TraverseContext;

class Traverse implements TraverseInterface
{
    public const STOP_BRANCH = 1;
    public const STOP_ALL = 2;

    /**
     * @var GraphRepositoryInterface
     */
    protected GraphRepositoryInterface $repository;

    /**
     * @param GraphRepositoryInterface $repository
     */
    public function __construct(GraphRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     * @return Generator<TraverseContextInterface>
     */
    public function generate(VertexInterface $start, TraverseFilterInterface $filter): Generator
    {
        $branchContext = $this->createBranchContext(0, null, $start);
        $globalPassedVertexesMap = [];
        $context = $this->createContext($start, null, $branchContext, [], $globalPassedVertexesMap);
        yield from $this->traverse($context, $filter);
    }

    /**
     * @param TraverseContextInterface $startContext
     * @param TraverseFilterInterface $filter
     * @return Generator<TraverseContextInterface>
     */
    protected function traverse(
        TraverseContextInterface $startContext,
        TraverseFilterInterface $filter
    ): Generator {
        $lastBranchIndex = $startContext->getBranchContext()->getIndex();
        $globalPassedVertexesMap = $startContext->getGlobalPassedVertexesMap();

        $contexts = new Queue([$startContext]);
        while(count($contexts)) {
            /** @var TraverseContextInterface $currentContext */
            $currentContext = $contexts->pop();
            $currentVertex = $currentContext->getVertex();
            $currentEdge = $currentContext->getEdge();

            if($filter->getHandleCondition($currentContext)->isSuitableVertex($currentContext->getVertex())) {
                $cmd = (yield $currentEdge => $currentContext);
                switch($cmd) {
                    case static::STOP_BRANCH:
                        yield $currentEdge => $currentContext;
                        continue 2;
                    case static::STOP_ALL:
                        return;
                }
            }

            $passedVertexesMap = $currentContext->getPassedVertexesMap();
            $passedVertexesMap[$currentVertex->getId()] = $currentVertex;
            $globalPassedVertexesMap[$currentVertex->getId()] = $currentVertex;

            $nextVertexes = $this->getNextVertexes($currentVertex, $filter->getPassCondition($currentContext));
            $i = 0;
            foreach($nextVertexes as $edge => $vertex) {
                $currentBranchContext = $currentContext->getBranchContext();
                if(count($nextVertexes) > 1 && $i > 0) {
                    $nextBranchContext = $this->createBranchContext(
                        ++$lastBranchIndex,
                        $currentBranchContext->getIndex(),
                        $currentVertex
                    );
                } else {
                    $nextBranchContext = $currentBranchContext;
                }

                $contexts->push($this->createContext(
                    $vertex,
                    $edge,
                    $nextBranchContext,
                    $passedVertexesMap,
                    $globalPassedVertexesMap
                ));

                ++$i;
            }
        }
    }

    /**
     * @param VertexInterface $vertex
     * @param FilterConditionInterface $condition
     * @return EdgeVertexPairsIteratorInterface
     */
    protected function getNextVertexes(
        VertexInterface $vertex,
        FilterConditionInterface $condition
    ): EdgeVertexPairsIteratorInterface {
        return EdgeVertexPairsIterator::combine(
            $this->repository->getNextVertexes($vertex, $condition),
            $this->repository->getPrevVertexes($vertex, $condition)
        );
    }

    /**
     * @param VertexInterface $vertex
     * @param EdgeInterface|null $edge
     * @param TraverseBranchContextInterface $branchContext
     * @param array<VertexInterface> $passedVertexesMap
     * @param array<VertexInterface> $globalPassedVertexesMap
     * @return TraverseContextInterface
     */
    protected function createContext(
        VertexInterface $vertex,
        ?EdgeInterface $edge,
        TraverseBranchContextInterface $branchContext,
        array $passedVertexesMap,
        array &$globalPassedVertexesMap
    ): TraverseContextInterface {
        return new TraverseContext($vertex, $edge, $branchContext, $passedVertexesMap, $globalPassedVertexesMap);
    }

    /**
     * @param int $index
     * @param int|null $parentIndex
     * @param VertexInterface $start
     * @return TraverseBranchContextInterface
     */
    protected function createBranchContext(
        int $index,
        ?int $parentIndex,
        VertexInterface $start
    ): TraverseBranchContextInterface {
        return new TraverseBranchContext($index, $parentIndex, $start);
    }
}
