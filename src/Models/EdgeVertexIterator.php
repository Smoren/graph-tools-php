<?php

namespace Smoren\GraphTools\Models;

use Countable;
use Iterator;
use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * Class EdgeVertexIterator
 * @package Smoren\GraphTools\Models
 * @implements Iterator<EdgeInterface|null, VertexInterface>
 */
class EdgeVertexIterator implements Iterator, Countable
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
