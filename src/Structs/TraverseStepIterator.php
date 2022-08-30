<?php

namespace Smoren\GraphTools\Structs;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseStepIteratorInterface;

/**
 * Class EdgeVertexIterator
 * @author <ofigate@gmail.com> Smoren
 */
class TraverseStepIterator implements TraverseStepIteratorInterface
{
    /**
     * @var array<TraverseStepItem> source to iterate
     */
    protected array $source;
    /**
     * @var int iteration pointer
     */
    protected int $index = 0;

    /**
     * @inheritDoc
     */
    public static function combine(TraverseStepIteratorInterface ...$iterators): TraverseStepIteratorInterface
    {
        $source = [];
        foreach($iterators as $iterator) {
            foreach($iterator as $edge => $vertex) {
                $source[] = new TraverseStepItem($edge, $vertex);
            }
        }
        return new TraverseStepIterator($source);
    }

    /**
     * EdgeVertexIterator constructor.
     * @param array<TraverseStepItem> $source
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
