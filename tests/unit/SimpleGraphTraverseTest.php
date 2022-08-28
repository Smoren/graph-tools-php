<?php

namespace Smoren\GraphTools\Tests\Unit;

use Codeception\Test\Unit;
use Generator;
use Smoren\GraphTools\Components\Traverse;
use Smoren\GraphTools\Components\TraverseDirect;
use Smoren\GraphTools\Components\TraverseReverse;
use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Filters\ConstTraverseFilter;
use Smoren\GraphTools\Filters\TransparentTraverseFilter;
use Smoren\GraphTools\Helpers\TraverseHelper;
use Smoren\GraphTools\Models\Connection;
use Smoren\GraphTools\Models\TraverseContext;
use Smoren\GraphTools\Models\Vertex;
use Smoren\GraphTools\Store\SimpleGraphRepository;
use Smoren\NestedAccessor\Helpers\NestedHelper;

class SimpleGraphTraverseTest extends Unit
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
        $traverse = new TraverseDirect($repo);
        $contexts = $traverse->generate($repo->getVertexById(1), new TransparentTraverseFilter());

        $vertexIds = [];
        foreach($contexts as $context) {
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([1, 2, 3], $vertexIds);

        $traverse = new TraverseReverse($repo);
        $contexts = $traverse->generate($repo->getVertexById(3), new TransparentTraverseFilter());

        $vertexIds = [];
        foreach($contexts as $context) {
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([3, 2, 1], $vertexIds);

        $traverse = new Traverse($repo);
        $contexts = $traverse->generate($repo->getVertexById(2), new TransparentTraverseFilter());

        $vertexIds = [];
        $loopsCount = 0;
        foreach($contexts as $context) {
            if($context->isLoop()) {
                $contexts->send(Traverse::STOP_BRANCH);
                ++$loopsCount;
            } else {
                $vertexIds[] = $context->getVertex()->getId();
            }
        }
        $this->assertEquals([2, 3, 1], $vertexIds);
        $this->assertEquals(2, $loopsCount);
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
        $traverse = new TraverseDirect($repo);
        $contexts = $traverse->generate($repo->getVertexById(1), new TransparentTraverseFilter());

        $vertexIds = [];
        foreach($contexts as $context) {
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([1, 2, 3, 1], $vertexIds);
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
            new Connection(9, 3, 4, 5),
        ];
        $repo = new SimpleGraphRepository($vertexes, $connections);
        $traverse = new TraverseDirect($repo);
        $contexts = $traverse->generate($repo->getVertexById(1), new TransparentTraverseFilter());

        $branchMap = TraverseHelper::getBranches($contexts);

        $this->assertEquals([1, 2, 3, 4, 1], NestedHelper::get($branchMap[0], 'id'));
        $this->assertEquals([1, 5, 6, 2, 3, 4, 1], NestedHelper::get($branchMap[1], 'id'));
        $this->assertEquals([1, 5, 3, 4, 1], NestedHelper::get($branchMap[2], 'id'));
        $this->assertEquals([1, 2, 3, 4, 5, 6, 2], NestedHelper::get($branchMap[3], 'id'));
        $this->assertEquals([1, 5, 3, 4, 5], NestedHelper::get($branchMap[4], 'id'));
        $this->assertEquals([1, 2, 3, 4, 5, 3], NestedHelper::get($branchMap[5], 'id'));
        $this->assertEquals([1, 5, 6, 2, 3, 4, 5], NestedHelper::get($branchMap[6], 'id'));

        // TODO test for only vertex or connections types
    }

    public function testStopBranch()
    {
        $vertexes = [
            new Vertex(1, 1, null),
            new Vertex(2, 1, null),
            new Vertex(3, 2, null), // (!) type == 2
            new Vertex(4, 1, null),
            new Vertex(5, 3, null), // (!) type == 3
            new Vertex(6, 1, null),
            new Vertex(7, 1, null),
            new Vertex(8, 1, null),
        ];
        $connections = [
            new Connection(1, 1, 1, 2),
            new Connection(2, 1, 2, 3),
            new Connection(3, 1, 3, 4),
            new Connection(4, 1, 2, 5),
            new Connection(5, 1, 5, 6),
            new Connection(6, 1, 2, 7),
            new Connection(7, 1, 7, 8),
        ];

        $repo = new SimpleGraphRepository($vertexes, $connections);
        $traverse = new TraverseDirect($repo);

        $contexts = $traverse->generate($repo->getVertexById(1), new TransparentTraverseFilter());
        $branchMap = TraverseHelper::getBranches($contexts);
        $this->assertCount(3, $branchMap);
        $this->assertEquals([1, 2, 3, 4], NestedHelper::get($branchMap[0], 'id'));
        $this->assertEquals([1, 2, 5, 6], NestedHelper::get($branchMap[1], 'id'));
        $this->assertEquals([1, 2, 7, 8], NestedHelper::get($branchMap[2], 'id'));

        $contexts = $traverse->generate($repo->getVertexById(1), new TransparentTraverseFilter());
        $branchMap = TraverseHelper::getBranches(
            $contexts,
            function(TraverseContext $context, Generator $contexts) {
                if($context->getVertex()->getType() === '2') {
                    $contexts->send(Traverse::STOP_BRANCH);
                }
            }
        );
        $this->assertCount(3, $branchMap);
        $this->assertEquals([1, 2, 3], NestedHelper::get($branchMap[0], 'id'));
        $this->assertEquals([1, 2, 5, 6], NestedHelper::get($branchMap[1], 'id'));
        $this->assertEquals([1, 2, 7, 8], NestedHelper::get($branchMap[2], 'id'));

        $filter = new ConstTraverseFilter((new FilterCondition())->setVertexTypesExclude([2]));
        $contexts = $traverse->generate($repo->getVertexById(1), $filter);
        $branchMap = TraverseHelper::getBranches($contexts);
        $this->assertCount(2, $branchMap);
        $this->assertEquals([1, 2, 5, 6], NestedHelper::get($branchMap[0], 'id'));
        $this->assertEquals([1, 2, 7, 8], NestedHelper::get($branchMap[1], 'id'));

        $contexts = $traverse->generate($repo->getVertexById(1), new TransparentTraverseFilter());
        $branchMap = TraverseHelper::getBranches(
            $contexts,
            function(TraverseContext $context, Generator $contexts) {
                if($context->getVertex()->getType() === '3') {
                    $contexts->send(Traverse::STOP_BRANCH);
                }
            }
        );
        $this->assertCount(3, $branchMap);
        $this->assertEquals([1, 2, 3, 4], NestedHelper::get($branchMap[0], 'id'));
        $this->assertEquals([1, 2, 5], NestedHelper::get($branchMap[1], 'id'));
        $this->assertEquals([1, 2, 7, 8], NestedHelper::get($branchMap[2], 'id'));

        $filter = new ConstTraverseFilter((new FilterCondition())->setVertexTypesExclude([3]));
        $contexts = $traverse->generate($repo->getVertexById(1), $filter);
        $branchMap = TraverseHelper::getBranches($contexts);
        $this->assertCount(2, $branchMap);
        $this->assertEquals([1, 2, 3, 4], NestedHelper::get($branchMap[0], 'id'));
        $this->assertEquals([1, 2, 7, 8], NestedHelper::get($branchMap[1], 'id'));

        $contexts = $traverse->generate($repo->getVertexById(1), new TransparentTraverseFilter());
        $branchMap = TraverseHelper::getBranches(
            $contexts,
            function(TraverseContext $context, Generator $contexts) {
                if(in_array($context->getVertex()->getType(), [2, 3])) {
                    $contexts->send(Traverse::STOP_BRANCH);
                }
            }
        );
        $this->assertCount(3, $branchMap);
        $this->assertEquals([1, 2, 3], NestedHelper::get($branchMap[0], 'id'));
        $this->assertEquals([1, 2, 5], NestedHelper::get($branchMap[1], 'id'));
        $this->assertEquals([1, 2, 7, 8], NestedHelper::get($branchMap[2], 'id'));

        $filter = new ConstTraverseFilter((new FilterCondition())->setVertexTypesExclude([2, 3]));
        $contexts = $traverse->generate($repo->getVertexById(1), $filter);
        $branchMap = TraverseHelper::getBranches($contexts);
        $this->assertCount(1, $branchMap);
        $this->assertEquals([1, 2, 7, 8], NestedHelper::get($branchMap[0], 'id'));
    }

    public function testWorkflowWithReverseLink()
    {
        $vertexes = [
            new Vertex(1, 1, null),
            new Vertex(2, 1, null),
            new Vertex(3, 1, null),
            new Vertex(4, 1, null),
            new Vertex(5, 1, null),
            new Vertex(6, 1, null),
            new Vertex(7, 1, null),
        ];
        $connections = [
            new Connection(1, 1, 1, 2),
            new Connection(2, 1, 2, 3),
            new Connection(3, 1, 3, 5),
            new Connection(4, 1, 2, 4),
            new Connection(5, 1, 4, 5),
            new Connection(6, 1, 4, 6),
            new Connection(7, 1, 6, 7),
            new Connection(8, 2, 6, 2),
        ];

        $repo = new SimpleGraphRepository($vertexes, $connections);
        $traverse = new TraverseDirect($repo);

        $contexts = $traverse->generate($repo->getVertexById(1), new TransparentTraverseFilter());
        $branches = TraverseHelper::getBranches($contexts);
        $this->assertCount(4, $branches);
        $this->assertEquals([1, 2, 3, 5], NestedHelper::get($branches[0], 'id'));
        $this->assertEquals([1, 2, 4, 5], NestedHelper::get($branches[1], 'id'));
        $this->assertEquals([1, 2, 4, 6, 7], NestedHelper::get($branches[2], 'id'));
        $this->assertEquals([1, 2, 4, 6, 2], NestedHelper::get($branches[3], 'id'));

        $filter = new ConstTraverseFilter((new FilterCondition())->setConnectionTypesOnly([1]));
        $contexts = $traverse->generate($repo->getVertexById(1), $filter);
        $branches = TraverseHelper::getBranches($contexts);
        $this->assertCount(3, $branches);
        $this->assertEquals([1, 2, 3, 5], NestedHelper::get($branches[0], 'id'));
        $this->assertEquals([1, 2, 4, 5], NestedHelper::get($branches[1], 'id'));
        $this->assertEquals([1, 2, 4, 6, 7], NestedHelper::get($branches[2], 'id'));
    }
}
