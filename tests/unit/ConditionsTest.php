<?php

namespace Smoren\GraphTools\Tests\Unit;

use Smoren\GraphTools\Conditions\EdgeCondition;
use Smoren\GraphTools\Conditions\VertexCondition;
use Smoren\GraphTools\Models\Edge;
use Smoren\GraphTools\Models\Vertex;

class ConditionsTest extends \Codeception\Test\Unit
{
    public function testVertexCondition()
    {
        $v1 = new Vertex(1, 1);
        $v2 = new Vertex(2, 1);
        $v3 = new Vertex(3, 2);

        $cond = new VertexCondition();
        $this->assertNull($cond->getVertexTypesOnly());
        $this->assertEquals([], $cond->getVertexTypesExcluded());
        $this->assertNull($cond->getVertexIdsOnly());
        $this->assertEquals([], $cond->getVertexIdsExcluded());
        $this->assertTrue($cond->isSuitableVertex($v1));
        $this->assertTrue($cond->isSuitableVertex($v2));
        $this->assertTrue($cond->isSuitableVertex($v3));

        $cond->onlyVertexTypes([1]);
        $this->assertEquals([1], $cond->getVertexTypesOnly());
        $this->assertEquals([], $cond->getVertexTypesExcluded());
        $this->assertNull($cond->getVertexIdsOnly());
        $this->assertEquals([], $cond->getVertexIdsExcluded());
        $this->assertTrue($cond->isSuitableVertex($v1));
        $this->assertTrue($cond->isSuitableVertex($v2));
        $this->assertFalse($cond->isSuitableVertex($v3));

        $cond->onlyVertexTypes(null)->excludeVertexTypes([2]);
        $this->assertNull($cond->getVertexTypesOnly());
        $this->assertEquals([2], $cond->getVertexTypesExcluded());
        $this->assertNull($cond->getVertexIdsOnly());
        $this->assertEquals([], $cond->getVertexIdsExcluded());
        $this->assertTrue($cond->isSuitableVertex($v1));
        $this->assertTrue($cond->isSuitableVertex($v2));
        $this->assertFalse($cond->isSuitableVertex($v3));

        $cond->excludeVertexIds([2, 3]);
        $this->assertNull($cond->getVertexTypesOnly());
        $this->assertEquals([2], $cond->getVertexTypesExcluded());
        $this->assertNull($cond->getVertexIdsOnly());
        $this->assertEquals([2, 3], $cond->getVertexIdsExcluded());
        $this->assertTrue($cond->isSuitableVertex($v1));
        $this->assertFalse($cond->isSuitableVertex($v2));
        $this->assertFalse($cond->isSuitableVertex($v3));

        $cond->excludeVertexIds([])->onlyVertexIds([1]);
        $this->assertNull($cond->getVertexTypesOnly());
        $this->assertEquals([2], $cond->getVertexTypesExcluded());
        $this->assertEquals([1], $cond->getVertexIdsOnly());
        $this->assertEquals([], $cond->getVertexIdsExcluded());
        $this->assertTrue($cond->isSuitableVertex($v1));
        $this->assertFalse($cond->isSuitableVertex($v2));
        $this->assertFalse($cond->isSuitableVertex($v3));
    }

    public function testEdgeCondition()
    {
        $e1 = new Edge(1, 1, 1, 2);
        $e2 = new Edge(2, 1, 2, 3);
        $e3 = new Edge(3, 2, 3, 1);

        $cond = new EdgeCondition();
        $this->assertNull($cond->getEdgeTypesOnly());
        $this->assertEquals([], $cond->getEdgeTypesExcluded());
        $this->assertTrue($cond->isSuitableEdge($e1));
        $this->assertTrue($cond->isSuitableEdge($e2));
        $this->assertTrue($cond->isSuitableEdge($e3));

        $cond->onlyEdgeTypes([1]);
        $this->assertEquals([1], $cond->getEdgeTypesOnly());
        $this->assertEquals([], $cond->getEdgeTypesExcluded());
        $this->assertTrue($cond->isSuitableEdge($e1));
        $this->assertTrue($cond->isSuitableEdge($e2));
        $this->assertFalse($cond->isSuitableEdge($e3));

        $cond->onlyEdgeTypes(null)->excludeEdgeTypes([2]);
        $this->assertNull($cond->getEdgeTypesOnly());
        $this->assertEquals([2], $cond->getEdgeTypesExcluded());
        $this->assertTrue($cond->isSuitableEdge($e1));
        $this->assertTrue($cond->isSuitableEdge($e2));
        $this->assertFalse($cond->isSuitableEdge($e3));
    }
}
