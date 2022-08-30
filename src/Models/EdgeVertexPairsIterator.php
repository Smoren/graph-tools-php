<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\EdgeVertexPairsIteratorInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * Class EdgeVertexIterator
 * @author <ofigate@gmail.com> Smoren
 */
class EdgeVertexPairsIterator implements EdgeVertexPairsIteratorInterface
{
    /**
     * @var array<EdgeVertexPair> source to iterate
     */
    protected array $source;
    /**
     * @var int iteration pointer
     */
    protected int $index = 0;

    /**
     * @inheritDoc
     */
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
     * EdgeVertexIterator constructor.
     * @param array<EdgeVertexPair> $source
     */
    public function __construct(array $source)
    {
        $this->source = $source;
    }

    /**
     * @inheritDoc
     */
    public function current(): VertexInterface
    {
        return $this->source[$this->index]->getVertex();
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->index++;
    }

    /**
     * @inheritDoc
     */
    public function key(): ?EdgeInterface
    {
        return $this->source[$this->index]->getEdge();
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return $this->index < $this->count();
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->source);
    }
}
