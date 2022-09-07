<?php

namespace Smoren\GraphTools\Tests\Unit\Models;

use Smoren\GraphTools\Models\Edge;
use Smoren\GraphTools\Tests\Unit\Structs\EdgeType;

class WorkflowEdge extends Edge
{
    protected static int $nextId = 1;

    public function __construct(string $fromId, string $toId)
    {
        parent::__construct(static::$nextId++, EdgeType::WORKFLOW, $fromId, $toId);
    }
}
