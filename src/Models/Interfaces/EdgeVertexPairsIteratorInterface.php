<?php

namespace Smoren\GraphTools\Models\Interfaces;

use Countable;
use Iterator;

/**
 * Interface EdgeVertexIteratorInterface
 * @package Smoren\GraphTools\Models\Interfaces
 * @extends \Iterator<EdgeInterface|null, VertexInterface>
 */
interface EdgeVertexPairsIteratorInterface extends Iterator, Countable
{
    public function current(): VertexInterface;
    public function next(): void;
    public function key(): ?EdgeInterface;
    public function valid(): bool;
    public function rewind(): void;
    public function count(): int;
}
