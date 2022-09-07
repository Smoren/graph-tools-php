<?php

namespace Smoren\GraphTools\Tests\Unit\Models;

use Smoren\GraphTools\Models\Vertex;
use Smoren\GraphTools\Tests\Unit\Models\Traits\OperatorTrait;
use Smoren\GraphTools\Tests\Unit\Structs\VertexType;

class OperatorXorVertex extends Vertex
{
    use OperatorTrait;

    public function __construct(string $id, $data = null)
    {
        parent::__construct($id, VertexType::OPERATOR_XOR, $data);
    }
}
