<?php

namespace Smoren\GraphTools\Store\Interfaces;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseStepIteratorInterface;

/**
 * Repository class to get graph data
 * @author <ofigate@gmail.com> Smoren
 */
interface GraphRepositoryInterface
{
    /**
     * Returns vertex object by it's id
     * @param string $id
     * @return VertexInterface
     */
    public function getVertexById(string $id): VertexInterface;

    /**
     * Returns edge object by it's id
     * @param string $id
     * @return EdgeInterface
     */
    public function getEdgeById(string $id): EdgeInterface;

    /**
     * Returns iterator of next vertexes of given vertex (EdgeInterface|null => VertexInterface)
     * @param VertexInterface $vertex vertex to get next of
     * @param FilterConditionInterface|null $condition filter condition
     * @return TraverseStepIteratorInterface next vertexes' iterator
     */
    public function getNextVertexes(
        VertexInterface $vertex,
        ?FilterConditionInterface $condition = null
    ): TraverseStepIteratorInterface;

    /**
     * Returns iterator of previous vertexes of given vertex (EdgeInterface|null => VertexInterface)
     * @param VertexInterface $vertex
     * @param FilterConditionInterface|null $condition
     * @return TraverseStepIteratorInterface
     */
    public function getPrevVertexes(
        VertexInterface $vertex,
        ?FilterConditionInterface $condition = null
    ): TraverseStepIteratorInterface;
}
