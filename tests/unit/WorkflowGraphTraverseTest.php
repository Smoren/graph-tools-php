<?php

namespace Smoren\GraphTools\Tests\Unit;

use Codeception\Test\Unit;
use Generator;
use Smoren\GraphTools\Tests\Unit\Models\EventVertex;
use Smoren\GraphTools\Tests\Unit\Models\FunctionVertex;
use Smoren\GraphTools\Tests\Unit\Models\OperatorAndVertex;
use Smoren\GraphTools\Tests\Unit\Models\WorkflowEdge;
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
use Smoren\NestedAccessor\Helpers\NestedHelper;

class WorkflowGraphTraverseTest extends Unit
{
    public function testSimpleChain()
    {
        $vertexes = [
            new EventVertex(1),
            new OperatorAndVertex(2),
            new FunctionVertex(3),
            new FunctionVertex(4),
            new EventVertex(5),
            new EventVertex(6),
            new OperatorAndVertex(7),
            new FunctionVertex(8),
            new EventVertex(9),
        ];
        $connections = [
            new WorkflowEdge(1, 2),
            new WorkflowEdge(2, 3),
            new WorkflowEdge(2, 4),
            new WorkflowEdge(3, 5),
            new WorkflowEdge(4, 6),
            new WorkflowEdge(5, 7),
            new WorkflowEdge(6, 7),
            new WorkflowEdge(7, 8),
            new WorkflowEdge(8, 9),
        ];

        $repo = new PreloadedGraphRepository($vertexes, $connections);

        $traverse = new TraverseDirect($repo);
        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );

        $vertexIds = [];
        /** @var TraverseContextInterface $context */
        foreach($contexts as $context) {
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 7, 8, 8, 9, 9], $vertexIds);
    }
}
