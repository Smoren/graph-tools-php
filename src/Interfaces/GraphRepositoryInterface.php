<?php

namespace Smoren\GraphTools\Interfaces;

interface GraphRepositoryInterface
{
    /**
     * @param string $id
     * @return VertexInterface
     */
    public function getVertexById(string $id): VertexInterface;

    /**
     * @param non-empty-string $vertexId
     * @param FilterConditionInterface $condition
     * @return array<VertexInterface>
     */
    public function getNextVertexes(string $vertexId, FilterConditionInterface $condition): array;

    /**
     * @param non-empty-string $vertexId
     * @param FilterConditionInterface $condition
     * @return array<VertexInterface>
     */
    public function getPrevVertexes(string $vertexId, FilterConditionInterface $condition): array;
}
