<?php

namespace Smoren\GraphTools\Tests\Unit\Models;

use Smoren\GraphTools\Models\Vertex;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Tests\Unit\Models\Traits\OperatorTrait;
use Smoren\GraphTools\Tests\Unit\Structs\VertexType;

class OperatorAndVertex extends Vertex
{
    use OperatorTrait;

    protected int $handleCount = 0;

    public function __construct(string $id, $data = null)
    {
        parent::__construct($id, VertexType::OPERATOR_AND, $data);
    }

    public function incrementHandleCount(): int
    {
        return ++$this->handleCount;
    }

    public function getHandleCount(): int
    {
        return $this->handleCount;
    }

    public function canPass(GraphRepositoryInterface $repo): bool
    {
        return $this->handleCount === $this->countInput($repo);
    }
}
