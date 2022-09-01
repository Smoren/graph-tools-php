<?php

namespace Smoren\GraphTools\Tests\Unit;

use Smoren\GraphTools\Models\Edge;
use Smoren\GraphTools\Models\Vertex;

class ModelsTest extends \Codeception\Test\Unit
{
    public function testVertex()
    {
        $vertex = new Vertex(1, 2, ['test' => 3]);
        $this->assertEquals(1, $vertex->getId());
        $this->assertEquals(2, $vertex->getType());
        $this->assertEquals(['test' => 3], $vertex->getData());
    }

    public function testEdge()
    {
        $edge = new Edge(1, 2, 3, 4, 5.5);
        $this->assertEquals(1, $edge->getId());
        $this->assertEquals(2, $edge->getType());
        $this->assertEquals(3, $edge->getFromId());
        $this->assertEquals(4, $edge->getToId());
        $this->assertEquals(5.5, $edge->getWeight());
    }
}
