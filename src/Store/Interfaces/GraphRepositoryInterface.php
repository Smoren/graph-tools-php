<?php

namespace Smoren\GraphTools\Store\Interfaces;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

interface GraphRepositoryInterface
{
    /**
     * @param string $id
     * @return VertexInterface
     */
    public function getVertexById(string $id): VertexInterface;

    /**
     * @param VertexInterface $vertex
     * @param FilterConditionInterface|null $condition
     * @return array<VertexInterface>
     */
    public function getNextVertexes(VertexInterface $vertex, ?FilterConditionInterface $condition = null): array;

    /**
     * @param VertexInterface $vertex
     * @param FilterConditionInterface|null $condition
     * @return array<VertexInterface>
     */
    public function getPrevVertexes(VertexInterface $vertex, ?FilterConditionInterface $condition = null): array;
}
