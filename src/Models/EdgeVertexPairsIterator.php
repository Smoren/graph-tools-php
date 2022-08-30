<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeVertexPairsIteratorInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * Class EdgeVertexIterator
 * @package Smoren\GraphTools\Models
 */
class EdgeVertexPairsIterator implements EdgeVertexPairsIteratorInterface
{
    public static function combine(EdgeVertexPairsIteratorInterface ...$iterators): EdgeVertexPairsIteratorInterface
    {
        $source = [];
        foreach($iterators as $iterator) {
            foreach($iterator as $edge => $vertex) {
                $source[] = new EdgeVertexPair($edge, $vertex);
            }
        }
        return new EdgeVertexPairsIterator($source);
    }

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
        return $this->index < $this->count();
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