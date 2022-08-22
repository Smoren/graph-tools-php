<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Interfaces\ConditionInterface;
use Smoren\GraphTools\Interfaces\ConnectionInterface;
use Smoren\GraphTools\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Interfaces\VertexInterface;
use Smoren\Schemator\Components\NestedStorage;

class SimpleGraphRepository implements GraphRepositoryInterface
{
    /**
     * @var array<int|string, VertexInterface>
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
                [(string)$connection->getFrom(), (string)$connection->getId()],
                [$connection->getType(), $connection->getTo()]
            );

            $this->connectionsDirectMap->set(
                [(string)$connection->getTo(), (string)$connection->getId()],
                [$connection->getType(), $connection->getFrom()]
            );
        }
    }

    /**
     * @param int|string $id
     * @return VertexInterface
     * @throws \Exception
     */
    public function getVertexById($id): VertexInterface
    {
        if(!isset($this->vertexMap[$id])) {
            throw new \Exception("vertex with id '{$id}' not exist"); // TODO
        }
        return $this->vertexMap[$id];
    }

    /**
     * @param VertexInterface $vertex
     * @param ConditionInterface $condition
     * @return array<VertexInterface>
     * @throws \Exception
     */
    public function getNextVertexes(VertexInterface $vertex, ConditionInterface $condition): array
    {
        return $this->getLinkedVertexesFromMap(
            $this->connectionsDirectMap,
            $vertex,
            $condition
        );
    }

    /**
     * @param VertexInterface $vertex
     * @param ConditionInterface $condition
     * @return array<VertexInterface>
     * @throws \Exception
     */
    public function getPrevVertexes(VertexInterface $vertex, ConditionInterface $condition): array
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
     * @param ConditionInterface $condition
     * @return array<VertexInterface>
     * @throws \Exception
     */
    protected function getLinkedVertexesFromMap(
        NestedStorage $source,
        VertexInterface $vertex,
        ConditionInterface $condition
    ): array {
        $result = [];
        /** @var array<array<int|string>> $vertexConnectionsMap */
        $vertexConnectionsMap = $source->get((string)$vertex->getId(), false) ?? [];
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
