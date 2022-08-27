<?php

namespace Smoren\GraphTools\Store;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Exceptions\RepositoryExceptionBase;
use Smoren\GraphTools\Models\Interfaces\ConnectionInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;
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
     * @inheritDoc
     * @throws RepositoryExceptionBase
     */
    public function getVertexById(string $id): VertexInterface
    {
        if(!isset($this->vertexMap[$id])) {
            throw new RepositoryExceptionBase(
                "vertex with id '{$id}' not exist",
                RepositoryExceptionBase::VERTEX_NOT_FOUND
            );
        }
        return $this->vertexMap[$id];
    }

    /**
     * @inheritDoc
     * @throws RepositoryExceptionBase
     */
    public function getNextVertexes(VertexInterface $vertex, ?FilterConditionInterface $condition = null): array
    {
        return $this->getLinkedVertexesFromMap(
            $this->connectionsDirectMap,
            $vertex,
            $condition
        );
    }

    /**
     * @inheritDoc
     * @throws RepositoryExceptionBase
     */
    public function getPrevVertexes(VertexInterface $vertex, ?FilterConditionInterface $condition = null): array
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
     * @param FilterConditionInterface|null $condition
     * @return array<VertexInterface>
     * @throws RepositoryExceptionBase
     */
    protected function getLinkedVertexesFromMap(
        array $source,
        VertexInterface $vertex,
        ?FilterConditionInterface $condition
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

    protected function hasVertexType(string $type, ?FilterConditionInterface $condition): bool
    {
        if($condition === null) {
            return true;
        }

        if(($only = $condition->getVertexTypesOnly()) !== null && !in_array($type, $only)) {
            return false;
        }

        return !in_array($type, $condition->getVertexTypesExclude());
    }

    protected function hasConnectionType(string $type, ?FilterConditionInterface $condition): bool
    {
        if($condition === null) {
            return true;
        }

        if(($only = $condition->getConnectionTypesOnly()) !== null && !in_array($type, $only)) {
            return false;
        }

        return !in_array($type, $condition->getConnectionTypesExclude());
    }
}
