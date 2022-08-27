<?php

namespace Smoren\GraphTools\Tests\Unit;

use Smoren\GraphTools\Components\TraverseOld;
use Smoren\GraphTools\Models\Connection;
use Smoren\GraphTools\Models\Vertex;
use Smoren\GraphTools\Store\SimpleGraphRepository;

class SimpleGraphTraverseTest extends \Codeception\Test\Unit
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
        $traverse = new TraverseOld($repo);
        $traverse->runForward($repo->getVertexById(1));
        $a = 1;
    }

    public function testSimpleLoopChain()
    {
        $vertexes = [
            new Vertex(1, 1, null),
            new Vertex(2, 1, null),
            new Vertex(3, 1, null),
        ];
        $connections = [
            new Connection(1, 1, 1, 2),
            new Connection(2, 1, 2, 3),
            new Connection(3, 1, 3, 1),
        ];
        $repo = new SimpleGraphRepository($vertexes, $connections);
        $traverse = new TraverseOld($repo);
        $traverse->runForward($repo->getVertexById(1));
        $a = 1;
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
        $traverse = new TraverseOld($repo);
        $traverse->runForward($repo->getVertexById(1));
        $a = 1;
    }
}
