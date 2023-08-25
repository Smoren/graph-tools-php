<?php

namespace Smoren\GraphTools\Store;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Exceptions\RepositoryException;
use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseStepIteratorInterface;
use Smoren\GraphTools\Structs\TraverseStepItem;
use Smoren\GraphTools\Structs\TraverseStepIterator;

/**
 * Graph repository implementation with data storage in RAM
 * @author Smoren <ofigate@gmail.com>
 */
class PreloadedGraphRepository implements GraphRepositoryInterface
{
    /**
     * @var array<string, VertexInterface> vertexes map by id
     */
    protected array $vertexMap = [];
    /**
     * @var array<string, EdgeInterface> edges map by id
     */
    protected array $edgesMap = [];
    /**
     * @var array<string, array<string, string[]>> links map: array<vertexFromId array<edgeId, [edgeType, vertexToId]>>
     */
    protected array $edgesDirectMap = [];
    /**
     * @var array<string, array<string, string[]>> links map: array<vertexToId array<edgeId, [edgeType, vertexFromId]>>
     */
    protected array $edgesReverseMap = [];

    /**
     * SimpleGraphRepository constructor
     * @param array<VertexInterface> $vertexes vertexes list
     * @param array<EdgeInterface> $edges edges list
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

            $this->setToMap(
                $this->edgesDirectMap,
                [$edge->getFromId(), $edge->getId()],
                [$edge->getType(), $edge->getToId()]
            );

            $this->setToMap(
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
    public function getEdgeById(string $id): EdgeInterface
    {
        if(!isset($this->edgesMap[$id])) {
            throw new RepositoryException(
                "edge with id '{$id}' not exist",
                RepositoryException::EDGE_NOT_FOUND
            );
        }
        return $this->edgesMap[$id];
    }

    /**
     * @inheritDoc
     * @throws RepositoryException
     */
    public function getNextVertexes(
        VertexInterface $vertex,
        ?FilterConditionInterface $condition = null
    ): TraverseStepIteratorInterface {
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
    public function getPrevVertexes(
        VertexInterface $vertex,
        ?FilterConditionInterface $condition = null
    ): TraverseStepIteratorInterface {
        return $this->getLinkedVertexesFromMap(
            $this->edgesReverseMap,
            $vertex,
            $condition
        );
    }

    /**
     * Get next vertexes from given map by current vertex
     * @param array<string, array<string, string[]>> $source source map
     * @param VertexInterface $vertex current vertex
     * @param FilterConditionInterface|null $condition filter condition
     * @return TraverseStepIteratorInterface next vertexes iterator
     * @throws RepositoryException
     */
    protected function getLinkedVertexesFromMap(
        array $source,
        VertexInterface $vertex,
        ?FilterConditionInterface $condition
    ): TraverseStepIteratorInterface {
        $result = [];
        foreach($source[$vertex->getId()] ?? [] as $edgeId => [$edgeType, $targetId]) {
            $edge = $this->getEdgeById($edgeId);
            if($this->isSuitableEdge($edge, $condition)) {
                $target = $this->getVertexById($targetId);
                if($this->isSuitableVertex($target, $condition)) {
                    $result[] = new TraverseStepItem($edge, $target);
                }
            }
        }
        return new TraverseStepIterator($result);
    }

    /**
     * Returns true if given vertex matches filter condition
     * @param VertexInterface $vertex vertex to check
     * @param FilterConditionInterface|null $condition filter condition
     * @return bool
     */
    protected function isSuitableVertex(VertexInterface $vertex, ?FilterConditionInterface $condition): bool
    {
        return ($condition === null) || $condition->isSuitableVertex($vertex);
    }

    /**
     * Returns true if given edge matches filter condition
     * @param EdgeInterface $edge edge to check
     * @param FilterConditionInterface|null $condition filter condition
     * @return bool
     */
    protected function isSuitableEdge(EdgeInterface $edge, ?FilterConditionInterface $condition): bool
    {
        return ($condition === null) || $condition->isSuitableEdge($edge);
    }

    /**
     * @param array<string, mixed> $map
     * @param array<string> $path
     * @param array<string> $value
     * @return void
     */
    protected function setToMap(array &$map, array $path, array $value): void
    {
        foreach ($path as $key) {
            /** @var array<string, mixed> $map */
            $map = &$map[$key];
        }
        $map = $value;
    }
}
