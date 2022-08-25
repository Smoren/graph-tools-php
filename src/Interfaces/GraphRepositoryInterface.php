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
     * @param FilterConditionInterface|null $condition
     * @return array<VertexInterface>
     */
    public function getNextVertexes(string $vertexId, ?FilterConditionInterface $condition = null): array;

    /**
     * @param non-empty-string $vertexId
     * @param FilterConditionInterface|null $condition
     * @return array<VertexInterface>
     */
    public function getPrevVertexes(string $vertexId, ?FilterConditionInterface $condition = null): array;
}
