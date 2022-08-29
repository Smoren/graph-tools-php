<?php

namespace Smoren\GraphTools\Store;

use Smoren\NestedAccessor\Helpers\NestedHelper;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Exceptions\RepositoryException;
use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;

class SimpleGraphRepository implements GraphRepositoryInterface
{
    /**
     * @var array<string, VertexInterface>
     */
    protected array $vertexMap = [];
    /**
     * @var array<string, EdgeInterface>
     */
    protected array $edgesMap = [];
    /**
     * @var array<string, array<string, string[]>>
     */
    protected array $edgesDirectMap = [];
    /**
     * @var array<string, array<string, string[]>>
     */
    protected array $edgesReverseMap = [];

    /**
     * @param array<VertexInterface> $vertexes
     * @param array<EdgeInterface> $edges
     */
    public function __construct(
        array $vertexes,
        array $edges
    ) {
        foreach($vertexes as $vertex) {
            $this->vertexMap[$vertex->getId()] = $vertex;
        }

        foreach($edges as $edge) {
            $this->edgesMap[$edge->getId()] = $edge;

            NestedHelper::set(
                $this->edgesDirectMap,
                [$edge->getFromId(), $edge->getId()],
                [$edge->getType(), $edge->getToId()]
            );

            NestedHelper::set(
                $this->edgesReverseMap,
                [$edge->getToId(), $edge->getId()],
                [$edge->getType(), $edge->getFromId()]
            );
        }
    }

    /**
     * @inheritDoc
     * @throws RepositoryException
     */
    public function getVertexById(string $id): VertexInterface
    {
        if(!isset($this->vertexMap[$id])) {
            throw new RepositoryException(
                "vertex with id '{$id}' not exist",
                RepositoryException::VERTEX_NOT_FOUND
            );
        }
        return $this->vertexMap[$id];
    }

    /**
     * @inheritDoc
     * @throws RepositoryException
     */
    public function getNextVertexes(VertexInterface $vertex, ?FilterConditionInterface $condition = null): array
    {
        return $this->getLinkedVertexesFromMap(
            $this->edgesDirectMap,
            $vertex,
            $condition
        );
    }

    /**
     * @inheritDoc
     * @throws RepositoryException
     */
    public function getPrevVertexes(VertexInterface $vertex, ?FilterConditionInterface $condition = null): array
    {
        return $this->getLinkedVertexesFromMap(
            $this->edgesReverseMap,
            $vertex,
            $condition
        );
    }

    /**
     * @param array<string, array<string, string[]>> $source
     * @param VertexInterface $vertex
     * @param FilterConditionInterface|null $condition
     * @return array<VertexInterface>
     * @throws RepositoryException
     */
    protected function getLinkedVertexesFromMap(
        array $source,
        VertexInterface $vertex,
        ?FilterConditionInterface $condition
    ): array {
        $result = [];
        foreach($source[$vertex->getId()] ?? [] as $edgeId => [$edgeType, $targetId]) {
            if($this->isSuitableEdge($this->edgesMap[$edgeId], $condition)) {
                $target = $this->getVertexById($targetId);
                if($this->isSuitableVertex($target, $condition)) {
                    $result[] = $target;
                }
            }
        }
        return $result;
    }

    /**
     * @param VertexInterface $vertex
     * @param FilterConditionInterface|null $condition
     * @return bool
     */
    protected function isSuitableVertex(VertexInterface $vertex, ?FilterConditionInterface $condition): bool
    {
        return ($condition === null) || $condition->isSuitableVertex($vertex);
    }

    /**
     * @param EdgeInterface $edge
     * @param FilterConditionInterface|null $condition
     * @return bool
     */
    protected function isSuitableEdge(EdgeInterface $edge, ?FilterConditionInterface $condition): bool
    {
        return ($condition === null) || $condition->isSuitableEdge($edge);
    }
}
