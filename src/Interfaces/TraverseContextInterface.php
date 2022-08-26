<?php

namespace Smoren\GraphTools\Interfaces;

interface TraverseContextInterface
{
    public function getVertex(): VertexInterface;
    public function getConnection(): ?ConnectionInterface;
    public function getFilterCondition(): FilterConditionInterface;
    public function getBranchIndex(): int;
}
