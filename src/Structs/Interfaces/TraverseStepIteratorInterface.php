<?php

namespace Smoren\GraphTools\Structs\Interfaces;

use Countable;
use Iterator;
use Smoren\GraphTools\Models\Interfaces\EdgeInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * Interface for iterator of EdgeVertexPairInterface
 * @author <ofigate@gmail.com> Smoren
 * @extends Iterator<EdgeInterface|null, VertexInterface>
 */
interface TraverseStepIteratorInterface extends Iterator, Countable
{
    /**
     * Combines several iterators to new iterator object
     * @param TraverseStepIteratorInterface ...$iterators input iterators
     * @return TraverseStepIteratorInterface new combined iterator
     */
    public static function combine(TraverseStepIteratorInterface ...$iterators): TraverseStepIteratorInterface;

    /**
     * @inheritDoc
     * @return VertexInterface
     */
    public function current(): VertexInterface;

    /**
     * @inheritDoc
     * @return void
     */
    public function next(): void;

    /**
     * @inheritDoc
     * @return EdgeInterface|null
     */
    public function key(): ?EdgeInterface;

    /**
     * @inheritDoc
     * @return bool
     */
    public function valid(): bool;

    /**
     * @inheritDoc
     * @return void
     */
    public function rewind(): void;

    /**
     * @inheritDoc
     * @return int
     */
    public function count(): int;
}
