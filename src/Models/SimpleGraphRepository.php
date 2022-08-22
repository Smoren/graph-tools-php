<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Interfaces\ConnectionInterface;
use Smoren\GraphTools\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Interfaces\VertexInterface;
use Smoren\Schemator\Components\NestedStorage;

class SimpleGraphRepository implements GraphRepositoryInterface
{
    /**
     * @var array<string, VertexInterface>
     */
    protected array $vertexMap = [];
    protected NestedStorage $connectionsDirectMap;
    protected NestedStorage $connectionsReverseMap;

    /**
     * @param array<VertexInterface> $vertexes
     * @param array<ConnectionInterface> $connections
     */
    public function __construct(
        array $vertexes,
        array $connections
    ) {
        $this->connectionsDirectMap = new NestedStorage();
        $this->connectionsReverseMap = new NestedStorage();

        foreach($vertexes as $vertex) {
            $this->vertexMap[$vertex->getId()] = $vertex;
        }

        foreach($connections as $connection) {
            $this->connectionsDirectMap->set(
                [$connection->getFrom(), $connection->getId()],
                [$connection->getType(), $connection->getTo()]
            );

            $this->connectionsDirectMap->set(
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
     * @param NestedStorage $source
     * @param VertexInterface $vertex
     * @param FilterConditionInterface $condition
     * @return array<VertexInterface>
     * @throws \Exception
     */
    protected function getLinkedVertexesFromMap(
        NestedStorage $source,
        VertexInterface $vertex,
        FilterConditionInterface $condition
    ): array {
        $result = [];
        /** @var array<array<string>> $vertexConnectionsMap */
        $vertexConnectionsMap = $source->get($vertex->getId(), false) ?? [];
        foreach($vertexConnectionsMap as [$connType, $targetId]) {
            if($condition->hasConnectionType($connType)) {
                $target = $this->getVertexById($targetId);
                if($condition->hasVertexType($target->getType())) {
                    $result[] = $target;
                }
            }
        }
        return $result;
    }
}
