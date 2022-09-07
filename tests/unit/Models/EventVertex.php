<?php

namespace Smoren\GraphTools\Tests\Unit\Models;

use Smoren\GraphTools\Models\Vertex;
use Smoren\GraphTools\Tests\Unit\Structs\VertexType;

class EventVertex extends Vertex
{
    public function __construct(string $id, $data = null)
    {
        parent::__construct($id, VertexType::EVENT, $data);
    }
}
