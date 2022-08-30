<?php

namespace Smoren\GraphTools\Store\Interfaces;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseStepIteratorInterface;

interface GraphRepositoryInterface
{
    /**
     * @param string $id
     * @return VertexInterface
     */
    public function getVertexById(string $id): VertexInterface;

    /**
     * @param string $id
     * @return EdgeInterface
     */
    public function getEdgeById(string $id): EdgeInterface;

    /**
     * @param VertexInterface $vertex
     * @param FilterConditionInterface|null $condition
     * @return TraverseStepIteratorInterface
     */
    public function getNextVertexes(
        VertexInterface $vertex,
        ?FilterConditionInterface $condition = null
    ): TraverseStepIteratorInterface;

    /**
     * @param VertexInterface $vertex
     * @param FilterConditionInterface|null $condition
     * @return TraverseStepIteratorInterface
     */
    public function getPrevVertexes(
        VertexInterface $vertex,
        ?FilterConditionInterface $condition = null
    ): TraverseStepIteratorInterface;
}
