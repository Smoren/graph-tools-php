<?php

namespace Smoren\GraphTools\Tests\Unit;

use Smoren\GraphTools\Models\Edge;
use Smoren\GraphTools\Models\Vertex;

class ModelsTest extends \Codeception\Test\Unit
{
    public function testVertex()
    {
        $vertex = new Vertex(1, 2, ['test' => 3]);
        $this->assertSame('1', $vertex->getId());
        $this->assertSame('2', $vertex->getType());
        $this->assertEquals(['test' => 3], $vertex->getData());
    }

    public function testEdge()
    {
        $edge = new Edge(1, 2, 3, 4, 5.5);
        $this->assertSame('1', $edge->getId());
        $this->assertSame('2', $edge->getType());
        $this->assertSame('3', $edge->getFromId());
        $this->assertSame('4', $edge->getToId());
        $this->assertEquals(5.5, $edge->getWeight());
    }
}
