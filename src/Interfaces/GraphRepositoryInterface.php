<?php

namespace Smoren\GraphTools\Interfaces;

interface GraphRepositoryInterface
{
    /**
     * @param int|string $id
     * @return VertexInterface
     */
    public function getVertexById($id): VertexInterface;

    /**
     * @param VertexInterface $vertex
     * @param ConditionInterface $condition
     * @return array<VertexInterface>
     */
    public function getNextVertexes(VertexInterface $vertex, ConditionInterface $condition): array;

    /**
     * @param VertexInterface $vertex
     * @param ConditionInterface $condition
     * @return array<VertexInterface>
     */
    public function getPrevVertexes(VertexInterface $vertex, ConditionInterface $condition): array;
}
