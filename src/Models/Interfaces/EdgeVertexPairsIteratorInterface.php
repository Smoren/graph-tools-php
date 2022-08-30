<?php

namespace Smoren\GraphTools\Models\Interfaces;

use Countable;
use Iterator;

/**
 * Interface for iterator of EdgeVertexPairInterface
 * @author <ofigate@gmail.com> Smoren
 * @extends Iterator<EdgeInterface|null, VertexInterface>
 */
interface EdgeVertexPairsIteratorInterface extends Iterator, Countable
{
    /**
     * Combines several iterators to new iterator object
     * @param EdgeVertexPairsIteratorInterface ...$iterators input iterators
     * @return EdgeVertexPairsIteratorInterface new combined iterator
     */
    public static function combine(EdgeVertexPairsIteratorInterface ...$iterators): EdgeVertexPairsIteratorInterface;

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
