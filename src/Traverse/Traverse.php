<?php

namespace Smoren\GraphTools\Traverse;

use Ds\Collection;
use Ds\Queue;
use Ds\Stack;
use Generator;
use Smoren\GraphTools\Traverse\Interfaces\TraverseInterface;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseBranchContextInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseStepIteratorInterface;
use Smoren\GraphTools\Structs\TraverseBranchContext;
use Smoren\GraphTools\Structs\TraverseContext;
use Smoren\GraphTools\Structs\TraverseStepIterator;

/**
 * Class for non-directional graph traversing
 * @author <ofigate@gmail.com> Smoren
 */
class Traverse implements TraverseInterface
{
    /**
     * Stop branch command
     */
    public const STOP_BRANCH = 1;
    /**
     * Stop all branches command
     */
    public const STOP_ALL = 2;

    /**
     * Wide traverse mode
     */
    public const MODE_WIDE = 1;
    /**
     * Deep traverse mode
     */
    public const MODE_DEEP = 2;

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
    public function generate(
        VertexInterface $start,
        TraverseFilterInterface $filter,
        int $traverseMode = self::MODE_WIDE
    ): Generator {
        $branchContext = $this->createBranchContext(0, null, $start);
        $globalPassedVertexesMap = [];
        $context = $this->createContext($start, null, $branchContext, [], $globalPassedVertexesMap);
        yield from $this->traverse($context, $filter, $traverseMode);
    }

    /**
     * Graph traverse generator
     * @param TraverseContextInterface $startContext traverse context of the first vertex
     * @param TraverseFilterInterface $filter traverse filter
     * @param int $traverseMode traverse mode (wide or deep)
     * @return Generator<TraverseContextInterface>
     */
    protected function traverse(
        TraverseContextInterface $startContext,
        TraverseFilterInterface $filter,
        int $traverseMode
    ): Generator {
        $lastBranchIndex = $startContext->getBranchContext()->getIndex();
        $globalPassedVertexesMap = $startContext->getGlobalPassedVertexesMap();
        $contexts = $this->createContextCollection($traverseMode, $startContext);

        while(count($contexts)) {
            /** @var TraverseContextInterface $currentContext */
            $currentContext = $contexts->pop();
            $currentVertex = $currentContext->getVertex();

            if($filter->matchesHandleCondition($currentContext)) {
                $cmd = (yield $currentContext);
                switch($cmd) {
                    case static::STOP_BRANCH:
                        yield $currentContext;
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
     * Returns traverse iterator for the next step of traversing
     * @param VertexInterface $vertex vertex to traverse from
     * @param FilterConditionInterface $condition pass condition
     * @return TraverseStepIteratorInterface next vertexes iterator
     */
    protected function getNextVertexes(
        VertexInterface $vertex,
        FilterConditionInterface $condition
    ): TraverseStepIteratorInterface {
        return TraverseStepIterator::combine(
            $this->repository->getNextVertexes($vertex, $condition),
            $this->repository->getPrevVertexes($vertex, $condition)
        );
    }

    /**
     * Creates context collection
     * @param int $traverseMode traverse mode
     * @param TraverseContextInterface $startContext start context
     * @return Queue<TraverseContextInterface>|Stack<TraverseContextInterface>
     */
    protected function createContextCollection(
        int $traverseMode,
        TraverseContextInterface $startContext
    ): Collection {
        if($traverseMode === self::MODE_WIDE) {
            $contexts = new Queue([$startContext]);
        } else {
            $contexts = new Stack([$startContext]);
        }

        return $contexts;
    }

    /**
     * Creates new traverse context instance
     * @param VertexInterface $vertex current vertex
     * @param EdgeInterface|null $edge current edge leading to current vertex
     * @param TraverseBranchContextInterface $branchContext branch context
     * @param array<VertexInterface> $passedVertexesMap map of passed vertexes in current branch
     * @param array<VertexInterface> $globalPassedVertexesMap map of all the passed vertexes
     * @return TraverseContextInterface traverse context
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
     * Creates new branch context instance
     * @param int $index branch index
     * @param int|null $parentIndex parent branch index
     * @param VertexInterface $start vertex which started this branch
     * @return TraverseBranchContextInterface new branch context
     */
    protected function createBranchContext(
        int $index,
        ?int $parentIndex,
        VertexInterface $start
    ): TraverseBranchContextInterface {
        return new TraverseBranchContext($index, $parentIndex, $start);
    }
}
