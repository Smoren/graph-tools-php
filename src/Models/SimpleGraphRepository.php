<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Interfaces\ConnectionInterface;
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
                [$connection->getFrom(), $connection->getId()],
                [$connection->getType(), $connection->getTo()]
            );

            $reverseMapAccessor->set(
                [$connection->getTo(), $connection->getId()],
                [$connection->getType(), $connection->getFrom()]
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
            throw new \Exception("vertex with id '{$id}' not exist"); // TODO
        }
        return $this->vertexMap[$id];
    }

    /**
     * @param VertexInterface $vertex
     * @param FilterConditionInterface $condition
     * @return array<VertexInterface>
     * @throws \Exception
     */
    public function getNextVertexes(VertexInterface $vertex, FilterConditionInterface $condition): array
    {
        return $this->getLinkedVertexesFromMap(
            $this->connectionsDirectMap,
            $vertex,
            $condition
        );
    }

    /**
     * @param VertexInterface $vertex
     * @param FilterConditionInterface $condition
     * @return array<VertexInterface>
     * @throws \Exception
     */
    public function getPrevVertexes(VertexInterface $vertex, FilterConditionInterface $condition): array
    {
        return $this->getLinkedVertexesFromMap(
            $this->connectionsReverseMap,
            $vertex,
            $condition
        );
    }

    /**
     * @param array<string, array<string, string[]>> $source
     * @param VertexInterface $vertex
     * @param FilterConditionInterface $condition
     * @return array<VertexInterface>
     * @throws \Exception
     */
    protected function getLinkedVertexesFromMap(
        array $source,
        VertexInterface $vertex,
        FilterConditionInterface $condition
    ): array {
        $result = [];
        foreach($source[$vertex->getId()] ?? [] as [$connType, $targetId]) {
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
