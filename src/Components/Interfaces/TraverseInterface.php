<?php

namespace Smoren\GraphTools\Components\Interfaces;

use Generator;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;

interface TraverseInterface
{
    /**
     * @param VertexInterface $start
     * @param TraverseFilterInterface $filter
     * @return Generator<TraverseContextInterface>
     */
    public function generate(VertexInterface $start, TraverseFilterInterface $filter): Generator;
}
