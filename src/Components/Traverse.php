<?php

namespace Smoren\GraphTools\Components;

use Ds\Queue;
use Generator;
use Smoren\GraphTools\Components\Interfaces\TraverseInterface;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Models\Interfaces\TraverseBranchContextInterface;
use Smoren\GraphTools\Models\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Models\TraverseBranchContext;
use Smoren\GraphTools\Models\TraverseContext;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;

abstract class Traverse implements TraverseInterface
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
        $context = $this->createContext($start, $branchContext, []);
        yield from $this->traverse($context, $filter);
    }

    /**
     * @param VertexInterface $vertex
     * @param TraverseBranchContextInterface $branchContext
     * @param array<VertexInterface> $passedVertexesMap
     * @return TraverseContextInterface
     */
    protected function createContext(
        VertexInterface $vertex,
        TraverseBranchContextInterface $branchContext,
        array $passedVertexesMap
    ): TraverseContextInterface {
        return new TraverseContext($vertex, $branchContext, $passedVertexesMap);
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

        $contexts = new Queue([$startContext]);
        while(count($contexts)) {
            /** @var TraverseContextInterface $currentContext */
            $currentContext = $contexts->pop();
            $currentVertex = $currentContext->getVertex();

            if($filter->getHandleCondition($currentContext)->isSuitableVertex($currentContext->getVertex())) {
                $cmd = (yield $currentContext);
                switch($cmd) {
                    case static::STOP_BRANCH:
                        continue 2;
                    case static::STOP_ALL:
                        return;
                }
            }

            $passedVertexesMap = $currentContext->getPassedVertexesMap();
            $passedVertexesMap[$currentVertex->getId()] = $currentVertex;

            $nextVertexes = $this->getNextVertexes($currentVertex, $filter->getPassCondition($currentContext));
            foreach($nextVertexes as $i => $vertex) {
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
                    $nextBranchContext,
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
