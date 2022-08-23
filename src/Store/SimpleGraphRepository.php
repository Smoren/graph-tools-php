<?php

namespace Smoren\GraphTools\Store;

use Smoren\GraphTools\Exceptions\RepositoryException;
use Smoren\GraphTools\Interfaces\ConnectionInterface;
use Smoren\GraphTools\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Interfaces\VertexInterface;
use Smoren\Schemator\Components\NestedAccessor;

class SimpleGraphRepository implements GraphRepositoryInterface
{
    /**
     * @var array<string, VertexInterface>
     */
    protected array $vertexMap = [];
    /**
     * @var array<string, array<string, string[]>>
     */
    protected array $connectionsDirectMap = [];
    /**
     * @var array<string, array<string, string[]>>
     */
    protected array $connectionsReverseMap = [];

    /**
     * @param array<VertexInterface> $vertexes
     * @param array<ConnectionInterface> $connections
     */
    public function __construct(
        array $vertexes,
        array $connections
    ) {
        foreach($vertexes as $vertex) {
            $this->vertexMap[$vertex->getId()] = $vertex;
        }

        $directMapAccessor = new NestedAccessor($this->connectionsDirectMap);
        $reverseMapAccessor = new NestedAccessor($this->connectionsReverseMap);

        foreach($connections as $connection) {
            $directMapAccessor->set(
                [$connection->getFromId(), $connection->getId()],
                [$connection->getType(), $connection->getToId()]
            );

            $reverseMapAccessor->set(
                [$connection->getToId(), $connection->getId()],
                [$connection->getType(), $connection->getFromId()]
            );
        }
    }

    /**
     * @param string $id
     * @return VertexInterface
     * @throws \Exception
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
     * @param non-empty-string $vertexId
     * @param FilterConditionInterface $condition
     * @return array<VertexInterface>
     * @throws \Exception
     */
    public function getNextVertexes(string $vertexId, FilterConditionInterface $condition): array
    {
        return $this->getLinkedVertexesFromMap(
            $this->connectionsDirectMap,
            $vertexId,
            $condition
        );
    }

    /**
     * @param non-empty-string $vertexId
     * @param FilterConditionInterface $condition
     * @return array<VertexInterface>
     * @throws \Exception
     */
    public function getPrevVertexes(string $vertexId, FilterConditionInterface $condition): array
    {
        return $this->getLinkedVertexesFromMap(
            $this->connectionsReverseMap,
            $vertexId,
            $condition
        );
    }

    /**
     * @param array<string, array<string, string[]>> $source
     * @param non-empty-string $vertexId
     * @param FilterConditionInterface $condition
     * @return array<VertexInterface>
     * @throws \Exception
     */
    protected function getLinkedVertexesFromMap(
        array $source,
        string $vertexId,
        FilterConditionInterface $condition
    ): array {
        $result = [];
        foreach($source[$vertexId] ?? [] as [$connType, $targetId]) {
            if($this->hasConnectionType($connType, $condition)) {
                $target = $this->getVertexById($targetId);
                if($this->hasVertexType($target->getType(), $condition)) {
                    $result[] = $target;
                }
            }
        }
        return $result;
    }

    protected function hasVertexType(string $type, FilterConditionInterface $condition): bool
    {
        if(($only = $condition->getVertexTypesOnly()) !== null && !in_array($type, $only)) {
            return false;
        }

        return !in_array($type, $condition->getVertexTypesExclude());
    }

    protected function hasConnectionType(string $type, FilterConditionInterface $condition): bool
    {
        if(($only = $condition->getConnectionTypesOnly()) !== null && !in_array($type, $only)) {
            return false;
        }

        return !in_array($type, $condition->getConnectionTypesExclude());
    }
}
