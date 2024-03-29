<?php

namespace Smoren\GraphTools\Tests\Unit;

use Codeception\Test\Unit;
use Generator;
use Smoren\GraphTools\Traverse\Traverse;
use Smoren\GraphTools\Traverse\TraverseDirect;
use Smoren\GraphTools\Traverse\TraverseReverse;
use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Filters\ConstTraverseFilter;
use Smoren\GraphTools\Filters\TransparentTraverseFilter;
use Smoren\GraphTools\Helpers\TraverseHelper;
use Smoren\GraphTools\Models\Edge;
use Smoren\GraphTools\Models\Vertex;
use Smoren\GraphTools\Store\PreloadedGraphRepository;
use Smoren\GraphTools\Structs\FilterConfig;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Structs\TraverseContext;

class PreloadedGraphTraverseTest extends Unit
{
    public function testSimpleChain()
    {
        $vertexes = [
            new Vertex(1, 1, null),
            new Vertex(2, 1, null),
            new Vertex(3, 1, null),
        ];
        $connections = [
            new Edge(1, 1, 1, 2),
            new Edge(2, 1, 2, 3),
        ];
        $repo = new PreloadedGraphRepository($vertexes, $connections);
        $traverse = new TraverseDirect($repo);
        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );

        $vertexIds = [];
        foreach($contexts as $context) {
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([1, 2, 3], $vertexIds);

        $traverse = new TraverseReverse($repo);
        $contexts = $traverse->generate(
            $repo->getVertexById(3),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );

        $vertexIds = [];
        foreach($contexts as $context) {
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([3, 2, 1], $vertexIds);

        $traverse = new Traverse($repo);

        $contexts = $traverse->generate(
            $repo->getVertexById(2),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );
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
        $this->assertSame(2, $loopsCount);

        $contexts = $traverse->generate(
            $repo->getVertexById(2),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS, FilterConfig::PREVENT_LOOP_HANDLE])
        );
        $vertexIds = [];
        foreach($contexts as $context) {
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([2, 3, 1], $vertexIds);

        $contexts = $traverse->generate(
            $repo->getVertexById(2),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS, FilterConfig::PREVENT_RETURN_BACK_PASS])
        );
        $vertexIds = [];
        foreach($contexts as $context) {
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([2, 3, 1], $vertexIds);
    }

    public function testSimpleLoopChain()
    {
        $vertexes = [
            new Vertex(1, 1, null),
            new Vertex(2, 1, null),
            new Vertex(3, 1, null),
        ];
        $connections = [
            new Edge(1, 1, 1, 2),
            new Edge(2, 1, 2, 3),
            new Edge(3, 1, 3, 1),
        ];
        $repo = new PreloadedGraphRepository($vertexes, $connections);
        $traverse = new TraverseDirect($repo);

        /** @var Generator<TraverseContextInterface> $contexts */
        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );
        $vertexIds = [];
        $edgeIds = [];
        /** @var TraverseContextInterface $context */
        foreach($contexts as $context) {
            $vertex = $context->getVertex();
            $edge = $context->getEdge();
            $vertexIds[] = $vertex->getId();
            $edgeIds[] = $edge !== null ? $edge->getId() : null;
        }
        $this->assertEquals([1, 2, 3, 1], $vertexIds);
        $this->assertEquals([null, 1, 2, 3], $edgeIds);

        /** @var Generator<TraverseContextInterface> $contexts */
        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS, FilterConfig::PREVENT_LOOP_HANDLE])
        );
        $vertexIds = [];
        foreach($contexts as $context) {
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([1, 2, 3], $vertexIds);

        /** @var Generator<TraverseContextInterface> $contexts */
        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([])
        );
        $loopsCount = 0;
        $vertexIds = [];
        foreach($contexts as $context) {
            if($context->getVertex()->getId() === '1' && $loopsCount++ === 3) {
                $contexts->send(Traverse::STOP_ALL);
            }
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([1, 2, 3, 1, 2, 3, 1, 2, 3, 1], $vertexIds);

        /** @var Generator<TraverseContextInterface> $contexts */
        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([])
        );
        $loopsCount = 0;
        $vertexIds = [];
        foreach($contexts as $context) {
            if($context->getVertex()->getId() === '1' && $loopsCount++ === 3) {
                $contexts->send(Traverse::STOP_ALL);
                continue;
            }
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([1, 2, 3, 1, 2, 3, 1, 2, 3], $vertexIds);
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
            new Edge(1, 1, 1, 2),
            new Edge(2, 1, 2, 3),
            new Edge(3, 1, 3, 4),
            new Edge(4, 1, 4, 1),
            new Edge(5, 2, 1, 5),
            new Edge(6, 1, 5, 6),
            new Edge(7, 2, 5, 3),
            new Edge(8, 2, 6, 2),
            new Edge(9, 3, 4, 5),
        ];
        $repo = new PreloadedGraphRepository($vertexes, $connections);
        $traverse = new TraverseDirect($repo);

        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );
        $branchMap = TraverseHelper::getBranches($contexts);
        $this->assertCount(7, $branchMap);
        $this->assertEquals([1, 2, 3, 4, 1], $this->getFromArray($branchMap[0], 'id'));
        $this->assertEquals([1, 5, 6, 2, 3, 4, 1], $this->getFromArray($branchMap[1], 'id'));
        $this->assertEquals([1, 5, 3, 4, 1], $this->getFromArray($branchMap[2], 'id'));
        $this->assertEquals([1, 2, 3, 4, 5, 6, 2], $this->getFromArray($branchMap[3], 'id'));
        $this->assertEquals([1, 5, 3, 4, 5], $this->getFromArray($branchMap[4], 'id'));
        $this->assertEquals([1, 2, 3, 4, 5, 3], $this->getFromArray($branchMap[5], 'id'));
        $this->assertEquals([1, 5, 6, 2, 3, 4, 5], $this->getFromArray($branchMap[6], 'id'));

        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS, FilterConfig::PREVENT_REPEAT_HANDLE])
        );
        $branchMap = TraverseHelper::getBranches($contexts);
        $this->assertCount(2, $branchMap);
        $this->assertEquals([1, 2, 3, 4], $this->getFromArray($branchMap[0], 'id'));
        $this->assertEquals([1, 5, 6], $this->getFromArray($branchMap[1], 'id'));

        // TODO test for only vertex or connections types
    }

    public function testTraverseMode()
    {
        $vertexes = [
            new Vertex(1, 1),
            new Vertex(11, 1),
            new Vertex(111, 1),
            new Vertex(112, 1),
            new Vertex(12, 1),
            new Vertex(121, 1),
            new Vertex(122, 1),
        ];
        $edges = [
            new Edge(1, 1, 1, 11),
            new Edge(2, 1, 1, 12),
            new Edge(3, 1, 11, 111),
            new Edge(4, 1, 11, 112),
            new Edge(5, 1, 12, 121),
            new Edge(6, 1, 12, 122),
        ];
        $repo = new PreloadedGraphRepository($vertexes, $edges);
        $traverse = new TraverseDirect($repo);

        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter(),
            Traverse::MODE_WIDE
        );
        $sequence = [];
        /** @var TraverseContextInterface $context */
        foreach($contexts as $context) {
            $sequence[] = $context->getVertex()->getId();
        }
        $this->assertEquals([1, 11, 12, 111, 112, 121, 122], $sequence);

        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter(),
            Traverse::MODE_DEEP
        );
        $sequence = [];
        /** @var TraverseContextInterface $context */
        foreach($contexts as $context) {
            $sequence[] = $context->getVertex()->getId();
        }
        $this->assertEquals([1, 12, 122, 121, 11, 112, 111], $sequence);
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
            new Edge(1, 1, 1, 2),
            new Edge(2, 1, 2, 3),
            new Edge(3, 1, 3, 4),
            new Edge(4, 1, 2, 5),
            new Edge(5, 1, 5, 6),
            new Edge(6, 1, 2, 7),
            new Edge(7, 1, 7, 8),
        ];

        $repo = new PreloadedGraphRepository($vertexes, $connections);
        $traverse = new TraverseDirect($repo);

        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );
        $branchMap = TraverseHelper::getBranches($contexts);
        $this->assertCount(3, $branchMap);
        $this->assertEquals([1, 2, 3, 4], $this->getFromArray($branchMap[0], 'id'));
        $this->assertEquals([1, 2, 5, 6], $this->getFromArray($branchMap[1], 'id'));
        $this->assertEquals([1, 2, 7, 8], $this->getFromArray($branchMap[2], 'id'));

        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );
        $branchMap = TraverseHelper::getBranches(
            $contexts,
            function(TraverseContext $context, Generator $contexts) {
                if($context->getVertex()->getType() === '2') {
                    $contexts->send(Traverse::STOP_BRANCH);
                }
            }
        );
        $this->assertCount(3, $branchMap);
        $this->assertEquals([1, 2, 3], $this->getFromArray($branchMap[0], 'id'));
        $this->assertEquals([1, 2, 5, 6], $this->getFromArray($branchMap[1], 'id'));
        $this->assertEquals([1, 2, 7, 8], $this->getFromArray($branchMap[2], 'id'));

        $filter = new ConstTraverseFilter(
            (new FilterCondition())->excludeVertexTypes([2]),
            null,
            [FilterConfig::PREVENT_LOOP_PASS]
        );
        $contexts = $traverse->generate($repo->getVertexById(1), $filter);
        $branchMap = TraverseHelper::getBranches($contexts);
        $this->assertCount(2, $branchMap);
        $this->assertEquals([1, 2, 5, 6], $this->getFromArray($branchMap[0], 'id'));
        $this->assertEquals([1, 2, 7, 8], $this->getFromArray($branchMap[1], 'id'));

        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );
        $branchMap = TraverseHelper::getBranches(
            $contexts,
            function(TraverseContext $context, Generator $contexts) {
                if($context->getVertex()->getType() === '3') {
                    $contexts->send(Traverse::STOP_BRANCH);
                }
            }
        );
        $this->assertCount(3, $branchMap);
        $this->assertEquals([1, 2, 3, 4], $this->getFromArray($branchMap[0], 'id'));
        $this->assertEquals([1, 2, 5], $this->getFromArray($branchMap[1], 'id'));
        $this->assertEquals([1, 2, 7, 8], $this->getFromArray($branchMap[2], 'id'));

        $filter = new ConstTraverseFilter(
            (new FilterCondition())->excludeVertexTypes([3]),
            null,
            [FilterConfig::PREVENT_LOOP_PASS]
        );
        $contexts = $traverse->generate($repo->getVertexById(1), $filter);
        $branchMap = TraverseHelper::getBranches($contexts);
        $this->assertCount(2, $branchMap);
        $this->assertEquals([1, 2, 3, 4], $this->getFromArray($branchMap[0], 'id'));
        $this->assertEquals([1, 2, 7, 8], $this->getFromArray($branchMap[1], 'id'));

        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );
        $branchMap = TraverseHelper::getBranches(
            $contexts,
            function(TraverseContext $context, Generator $contexts) {
                if(in_array($context->getVertex()->getType(), [2, 3])) {
                    $contexts->send(Traverse::STOP_BRANCH);
                }
            }
        );
        $this->assertCount(3, $branchMap);
        $this->assertEquals([1, 2, 3], $this->getFromArray($branchMap[0], 'id'));
        $this->assertEquals([1, 2, 5], $this->getFromArray($branchMap[1], 'id'));
        $this->assertEquals([1, 2, 7, 8], $this->getFromArray($branchMap[2], 'id'));

        $filter = new ConstTraverseFilter(
            (new FilterCondition())->excludeVertexTypes([2, 3]),
            null,
            [FilterConfig::PREVENT_LOOP_PASS]
        );
        $contexts = $traverse->generate($repo->getVertexById(1), $filter);
        $branchMap = TraverseHelper::getBranches($contexts);
        $this->assertCount(1, $branchMap);
        $this->assertEquals([1, 2, 7, 8], $this->getFromArray($branchMap[0], 'id'));
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
            new Edge(1, 1, 1, 2),
            new Edge(2, 1, 2, 3),
            new Edge(3, 1, 3, 5),
            new Edge(4, 1, 2, 4),
            new Edge(5, 1, 4, 5),
            new Edge(6, 1, 4, 6),
            new Edge(7, 1, 6, 7),
            new Edge(8, 2, 6, 2),
        ];

        $repo = new PreloadedGraphRepository($vertexes, $connections);
        $traverse = new TraverseDirect($repo);

        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );
        $branches = TraverseHelper::getBranches($contexts);
        $this->assertCount(4, $branches);
        $this->assertEquals([1, 2, 3, 5], $this->getFromArray($branches[0], 'id'));
        $this->assertEquals([1, 2, 4, 5], $this->getFromArray($branches[1], 'id'));
        $this->assertEquals([1, 2, 4, 6, 7], $this->getFromArray($branches[2], 'id'));
        $this->assertEquals([1, 2, 4, 6, 2], $this->getFromArray($branches[3], 'id'));

        $filter = new ConstTraverseFilter((new FilterCondition())->onlyEdgeTypes([1]));
        $contexts = $traverse->generate($repo->getVertexById(1), $filter);
        $branches = TraverseHelper::getBranches($contexts);
        $this->assertCount(3, $branches);
        $this->assertEquals([1, 2, 3, 5], $this->getFromArray($branches[0], 'id'));
        $this->assertEquals([1, 2, 4, 5], $this->getFromArray($branches[1], 'id'));
        $this->assertEquals([1, 2, 4, 6, 7], $this->getFromArray($branches[2], 'id'));
    }

    protected function getFromArray(array $source, string $key)
    {
        return array_map(fn ($item) => is_array($item) ? $item[$key] : $item->{'get'.ucfirst($key)}(), $source);
    }
}
