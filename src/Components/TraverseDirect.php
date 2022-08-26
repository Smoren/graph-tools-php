<?php

namespace Smoren\GraphTools\Components;

use Generator;
use Smoren\GraphTools\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Interfaces\TraverseHandlerInterface;
use Smoren\GraphTools\Interfaces\VertexInterface;

class TraverseDirect
{
    protected GraphRepositoryInterface $repository;

    public function __construct(GraphRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param VertexInterface $start
     * @param TraverseHandlerInterface $handler
     * @return Generator<TraverseContextInterface>
     */
    public function generate(VertexInterface $start, TraverseHandlerInterface $handler): Generator
    {

    }
}