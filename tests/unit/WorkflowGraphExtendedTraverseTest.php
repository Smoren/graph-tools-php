<?php

namespace Smoren\GraphTools\Tests\Unit;

use Codeception\Test\Unit;
use Generator;
use Smoren\GraphTools\Tests\Unit\Filters\WorkflowHiddenBranchingTraverseFilter;
use Smoren\GraphTools\Tests\Unit\Models\EventVertex;
use Smoren\GraphTools\Tests\Unit\Models\FunctionVertex;
use Smoren\GraphTools\Tests\Unit\Models\OperatorAndVertex;
use Smoren\GraphTools\Tests\Unit\Models\OperatorXorVertex;
use Smoren\GraphTools\Tests\Unit\Models\WorkflowEdge;
use Smoren\GraphTools\Tests\Unit\Traverse\WorkflowTraverse;
use Smoren\GraphTools\Traverse\Traverse;
use Smoren\GraphTools\Traverse\TraverseDirect;
use Smoren\GraphTools\Filters\TransparentTraverseFilter;
use Smoren\GraphTools\Store\PreloadedGraphRepository;
use Smoren\GraphTools\Structs\FilterConfig;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;

class WorkflowGraphExtendedTraverseTest extends Unit
{
    public function testSimpleAndBranching()
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
        $traverse = new WorkflowTraverse($repo);

        $contexts = $traverse->generate($repo->getVertexById(1), new TransparentTraverseFilter());

        $vertexIds = [];
        foreach($contexts as $context) {
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([1, 3, 4, 5, 6, 8, 9], $vertexIds);
    }

    public function testSimpleXorReturnBack()
    {
        $vertexes = [
            new EventVertex(1),
            new OperatorXorVertex(2),
            new FunctionVertex(3),
            new OperatorXorVertex(4),
            new EventVertex(5),
            new EventVertex(6),
        ];
        $connections = [
            new WorkflowEdge(1, 2),
            new WorkflowEdge(2, 3),
            new WorkflowEdge(3, 4),
            new WorkflowEdge(4, 5),
            new WorkflowEdge(4, 6),
            new WorkflowEdge(6, 2),
        ];

        $repo = new PreloadedGraphRepository($vertexes, $connections);
        $traverse = new WorkflowTraverse($repo);

        // =============================================
        // WorkflowTraverseFilter with PREVENT_LOOP_PASS
        // =============================================
        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );

        $vertexIds = [];
        /** @var TraverseContextInterface $context */
        foreach($contexts as $context) {
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([1, 3, 5], $vertexIds);

        // TODO вместе с логикой операторов ввести DynamicDataStorage
    }
    public function testSimpleAndBranchingAndVertexChange()
    {
        $rightVertex = new EventVertex(6);

        $vertexes = [
            new EventVertex(1),
            new FunctionVertex(2, $rightVertex),
            new OperatorXorVertex(3),
            new EventVertex(4),
            new FunctionVertex(5),
            $rightVertex,
            new FunctionVertex(7),
            new OperatorXorVertex(8),
            new EventVertex(9),
            new FunctionVertex(10),
        ];
        $connections = [
            new WorkflowEdge(1, 2),
            new WorkflowEdge(2, 3),
            new WorkflowEdge(3, 4),
            new WorkflowEdge(4, 5),
            new WorkflowEdge(5, 8),
            new WorkflowEdge(3, 6),
            new WorkflowEdge(6, 7),
            new WorkflowEdge(7, 8),
            new WorkflowEdge(8, 9),
            new WorkflowEdge(9, 10),
        ];

        $repo = new PreloadedGraphRepository($vertexes, $connections);
        $traverse = new WorkflowTraverse($repo);

        // =============================================
        // WorkflowTraverseFilter with PREVENT_LOOP_PASS
        // =============================================
        $contexts = $traverse->generate(
            $repo->getVertexById(1),
            new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
        );

        $vertexIds = [];
        /** @var TraverseContextInterface $context */
        foreach($contexts as $context) {
            $vertexIds[] = $context->getVertex()->getId();
        }
        $this->assertEquals([1, 2, 6, 7, 9, 10], $vertexIds);
    }
}
