<?php

namespace Smoren\GraphTools\Components\Interfaces;

use Generator;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Models\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

interface TraverseInterface
{
    /**
     * @param VertexInterface $start
     * @param TraverseFilterInterface $filter
     * @param bool $unique
     * @return Generator<TraverseContextInterface>
     */
    public function generate(VertexInterface $start, TraverseFilterInterface $filter, bool $unique = false): Generator;
}
