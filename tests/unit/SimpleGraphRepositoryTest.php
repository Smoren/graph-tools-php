<?php

namespace Smoren\GraphTools\Tests\Unit;

use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Exceptions\RepositoryExceptionBase;
use Smoren\GraphTools\Models\Connection;
use Smoren\GraphTools\Models\Vertex;
use Smoren\GraphTools\Store\SimpleGraphRepository;

class SimpleGraphRepositoryTest extends \Codeception\Test\Unit
{
    public function testSimpleChain()
    {
        $vertexes = [
            new Vertex(1, 1, null),
            new Vertex(2, 1, null),
            new Vertex(3, 1, null),
        ];
        $connections = [
            new Connection(1, 1, 1, 2),
            new Connection(2, 1, 2, 3),
        ];
        $repo = new SimpleGraphRepository($vertexes, $connections);

        $this->assertEquals(1, $repo->getVertexById(1)->getId());
        $this->assertEquals(2, $repo->getVertexById(2)->getId());
        $this->assertEquals(3, $repo->getVertexById(3)->getId());

        $this->assertVertexIds(
            [],
            $repo->getPrevVertexes($repo->getVertexById(1))
        );
        $this->assertVertexIds(
            [2],
            $repo->getNextVertexes($repo->getVertexById(1))
        );
        $this->assertVertexIds(
            [1],
            $repo->getPrevVertexes($repo->getVertexById(2), new FilterCondition())
        );
        $this->assertVertexIds(
            [3],
            $repo->getNextVertexes($repo->getVertexById(2), new FilterCondition())
        );

        $this->assertVertexIds(
            [3],
            $repo->getNextVertexes($repo->getVertexById(2), (new FilterCondition())->setVertexTypesOnly([1]))
        );
        $this->assertVertexIds(
            [],
            $repo->getNextVertexes($repo->getVertexById(2), (new FilterCondition())->setVertexTypesOnly([2]))
        );
        $this->assertVertexIds(
            [3],
            $repo->getNextVertexes($repo->getVertexById(2), (new FilterCondition())->setVertexTypesExclude([2]))
        );
        $this->assertVertexIds(
            [],
            $repo->getNextVertexes($repo->getVertexById(2), (new FilterCondition())->setVertexTypesExclude([1]))
        );

        $this->assertVertexIds(
            [3],
            $repo->getNextVertexes($repo->getVertexById(2), (new FilterCondition())->setConnectionTypesOnly([1]))
        );
        $this->assertVertexIds(
            [],
            $repo->getNextVertexes($repo->getVertexById(2), (new FilterCondition())->setConnectionTypesOnly([2]))
        );
        $this->assertVertexIds(
            [3],
            $repo->getNextVertexes($repo->getVertexById(2), (new FilterCondition())->setConnectionTypesExclude([2]))
        );
        $this->assertVertexIds(
            [],
            $repo->getNextVertexes($repo->getVertexById(2), (new FilterCondition())->setConnectionTypesExclude([1]))
        );
    }

    public function testWeb()
    {
        $vertexes = [
            new Vertex(1, 1, null),
            new Vertex(2, 1, null),
            new Vertex(3, 1, null),
            new Vertex(4, 1, null),
            new Vertex(5, 2, null),
            new Vertex(6, 2, null),
        ];
        $connections = [
            new Connection(1, 1, 1, 2),
            new Connection(2, 1, 2, 3),
            new Connection(3, 1, 3, 4),
            new Connection(4, 1, 4, 1),
            new Connection(5, 2, 1, 5),
            new Connection(6, 1, 5, 6),
            new Connection(7, 2, 5, 3),
            new Connection(8, 2, 6, 2),
        ];
        $repo = new SimpleGraphRepository($vertexes, $connections);

        $this->assertVertexIds(
            [2, 5],
            $repo->getNextVertexes($repo->getVertexById(1), new FilterCondition())
        );
        $this->assertVertexIds(
            [5],
            $repo->getNextVertexes($repo->getVertexById(1), (new FilterCondition())->setVertexTypesOnly([2]))
        );
        $this->assertVertexIds(
            [2],
            $repo->getNextVertexes($repo->getVertexById(1), (new FilterCondition())->setVertexTypesOnly([1]))
        );
        $this->assertVertexIds(
            [5],
            $repo->getNextVertexes($repo->getVertexById(1), (new FilterCondition())->setConnectionTypesOnly([2]))
        );
        $this->assertVertexIds(
            [2],
            $repo->getNextVertexes($repo->getVertexById(1), (new FilterCondition())->setConnectionTypesOnly([1]))
        );

        $this->assertVertexIds(
            [6, 3],
            $repo->getNextVertexes($repo->getVertexById(5), new FilterCondition())
        );
        $this->assertVertexIds(
            [6],
            $repo->getNextVertexes($repo->getVertexById(5), (new FilterCondition())->setConnectionTypesExclude([2]))
        );
        $this->assertVertexIds(
            [],
            $repo->getNextVertexes($repo->getVertexById(5), (new FilterCondition())
                ->setConnectionTypesExclude([2])
                ->setVertexTypesOnly([1]))
        );
        $this->assertVertexIds(
            [3, 6],
            $repo->getNextVertexes($repo->getVertexById(5), (new FilterCondition())
                ->setConnectionTypesOnly([1, 2])
                ->setVertexTypesOnly([1, 2]))
        );
        $this->assertVertexIds(
            [3],
            $repo->getNextVertexes($repo->getVertexById(5), (new FilterCondition())
                ->setConnectionTypesOnly([2])
                ->setVertexTypesOnly([1]))
        );
        $this->assertVertexIds(
            [6],
            $repo->getNextVertexes($repo->getVertexById(5), (new FilterCondition())
                ->setConnectionTypesOnly([1])
                ->setVertexTypesOnly([2]))
        );
        $this->assertVertexIds(
            [],
            $repo->getNextVertexes($repo->getVertexById(5), (new FilterCondition())
                ->setConnectionTypesOnly([1])
                ->setVertexTypesOnly([1]))
        );
        $this->assertVertexIds(
            [],
            $repo->getNextVertexes($repo->getVertexById(5), (new FilterCondition())
                ->setConnectionTypesOnly([2])
                ->setVertexTypesOnly([2]))
        );
    }

    public function testRepositoryException()
    {
        $vertexes = [
            new Vertex(1, 1, null),
            new Vertex(2, 1, null),
            new Vertex(3, 1, null),
        ];
        $connections = [
            new Connection(1, 1, 1, 2),
            new Connection(2, 1, 2, 3),
        ];
        $repo = new SimpleGraphRepository($vertexes, $connections);

        /** @var Vertex $vertex */
        $vertex = $repo->getVertexById(1);
        $this->assertEquals(1, $vertex->getId());
        $this->assertEquals(null, $vertex->getData());

        try {
            $repo->getVertexById(100);
            $this->expectError();
        } catch(RepositoryExceptionBase $e) {
            $this->assertEquals(RepositoryExceptionBase::VERTEX_NOT_FOUND, $e->getCode());
        }
    }

    /**
     * @param array<string> $expectedVertexIds
     * @param array<Vertex> $vertexes
     * @return void
     */
    protected function assertVertexIds(array $expectedVertexIds, array $vertexes)
    {
        $actualVertexIds = [];
        foreach($vertexes as $vertex) {
            $actualVertexIds[] = $vertex->getId();
        }

        sort($expectedVertexIds);
        sort($actualVertexIds);

        $this->assertEquals($expectedVertexIds, $actualVertexIds);
    }
}
