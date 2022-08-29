<?php

namespace Smoren\GraphTools\Store\Interfaces;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeVertexPairsIteratorInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

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
     * @return EdgeVertexPairsIteratorInterface
     */
    public function getNextVertexes(
        VertexInterface $vertex,
        ?FilterConditionInterface $condition = null
    ): EdgeVertexPairsIteratorInterface;

    /**
     * @param VertexInterface $vertex
     * @param FilterConditionInterface|null $condition
     * @return EdgeVertexPairsIteratorInterface
     */
    public function getPrevVertexes(
        VertexInterface $vertex,
        ?FilterConditionInterface $condition = null
    ): EdgeVertexPairsIteratorInterface;
}
