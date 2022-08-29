<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeVertexIteratorInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * Class EdgeVertexIterator
 * @package Smoren\GraphTools\Models
 */
class EdgeVertexIterator implements EdgeVertexIteratorInterface
{
    /**
     * @var array<EdgeVertexPair>
     */
    protected array $source;
    protected int $index = 0;

    /**
     * EdgeVertexIterator constructor.
     * @param array<EdgeVertexPair> $source
     */
    public function __construct(array $source)
    {
        $this->source = $source;
    }

    public function current(): VertexInterface
    {
        return $this->source[$this->index]->getVertex();
    }

    public function next(): void
    {
        $this->index++;
    }

    public function key(): ?EdgeInterface
    {
        return $this->source[$this->index]->getEdge();
    }

    public function valid(): bool
    {
        return isset($this->source[$this->index]);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    public function count(): int
    {
        return count($this->source);
    }
}
