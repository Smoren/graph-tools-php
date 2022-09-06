<?php

namespace Smoren\GraphTools\Traverse\Interfaces;

use Generator;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;

/**
 * Interface for graph traversing
 * @author Smoren <ofigate@gmail.com>
 */
interface TraverseInterface
{
    /**
     * Generator to iterate graph vertexes as edge|null => vertex (null for start vertex)
     * @param VertexInterface $start start vertex
     * @param TraverseFilterInterface $filter traverse filter
     * @param int $traverseMode traverse mode (wide or deep)
     * @return Generator<TraverseContextInterface>
     */
    public function generate(
        VertexInterface $start,
        TraverseFilterInterface $filter,
        int $traverseMode
    ): Generator;
}
