<?php

namespace Smoren\GraphTools\Tests\Unit;

use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Conditions\VertexCondition;
use Smoren\GraphTools\Filters\ConstTraverseFilter;
use Smoren\GraphTools\Filters\TransparentTraverseFilter;
use Smoren\GraphTools\Models\Vertex;
use Smoren\GraphTools\Structs\FilterConfig;
use Smoren\GraphTools\Structs\TraverseBranchContext;
use Smoren\GraphTools\Structs\TraverseContext;

class FiltersTest extends \Codeception\Test\Unit
{
    public function testTransparent()
    {
        $vertex = new Vertex(1, 1);
        $anotherVertex = new Vertex(2, 2);
        $passed = [$vertex->getId() => $vertex];
        $globalPassed = [$vertex->getId() => $vertex];
        $branchContext = new TraverseBranchContext(0, 0, $vertex);
        $contextLoop = new TraverseContext($vertex, null, $branchContext, $passed, $globalPassed);
        $contextNormal = new TraverseContext($anotherVertex, null, $branchContext, $passed, $globalPassed);

        $filter = new TransparentTraverseFilter();
        $cond = $filter->getPassCondition($contextLoop);
        $this->assertTrue($cond->isSuitableVertex($vertex)); // can pass
        $this->assertTrue($cond->isSuitableVertex($anotherVertex)); // can pass
        $cond = $filter->getHandleCondition($contextLoop);
        $this->assertTrue($cond->isSuitableVertex($vertex)); // can handle

        $filter = new TransparentTraverseFilter([FilterConfig::HANDLE_UNIQUE_VERTEXES]);
        $cond = $filter->getPassCondition($contextLoop);
        $this->assertTrue($cond->isSuitableVertex($vertex));        // can pass
        $this->assertTrue($cond->isSuitableVertex($anotherVertex)); // can pass
        $cond = $filter->getHandleCondition($contextLoop);
        $this->assertFalse($cond->isSuitableVertex($vertex));       // cannot handle twice
        $cond = $filter->getHandleCondition($contextNormal);
        $this->assertTrue($cond->isSuitableVertex($anotherVertex)); // can handle

        $filter = new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_HANDLE]);
        $cond = $filter->getPassCondition($contextLoop);
        $this->assertTrue($cond->isSuitableVertex($vertex));        // can pass
        $this->assertTrue($cond->isSuitableVertex($anotherVertex)); // can pass
        $cond = $filter->getHandleCondition($contextLoop);
        $this->assertFalse($cond->isSuitableVertex($vertex));       // cannot handle when loop
        $cond = $filter->getHandleCondition($contextNormal);
        $this->assertTrue($cond->isSuitableVertex($anotherVertex)); // can handle without loop

        $filter = new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS]);
        $cond = $filter->getPassCondition($contextLoop);
        $this->assertFalse($cond->isSuitableVertex($vertex));        // cannot pass because loop in ctx
        $this->assertFalse($cond->isSuitableVertex($anotherVertex)); // cannot pass because loop in ctx
        $cond = $filter->getPassCondition($contextNormal);
        $this->assertTrue($cond->isSuitableVertex($vertex));         // can handle
        $cond = $filter->getHandleCondition($contextLoop);
        $this->assertTrue($cond->isSuitableVertex($anotherVertex));  // can handle

        $filter = new TransparentTraverseFilter([FilterConfig::PREVENT_RETURN_BACK_PASS]);
        $cond = $filter->getPassCondition($contextLoop);
        $this->assertFalse($cond->isSuitableVertex($vertex));       // cannot pass to return back
        $this->assertTrue($cond->isSuitableVertex($anotherVertex)); // can pass
        $cond = $filter->getPassCondition($contextNormal);
        $this->assertFalse($cond->isSuitableVertex($vertex));       // cannot pass to return back
        $this->assertTrue($cond->isSuitableVertex($anotherVertex)); // can pass
        $cond = $filter->getHandleCondition($contextLoop);
        $this->assertTrue($cond->isSuitableVertex($vertex));        // can handle
        $cond = $filter->getHandleCondition($contextNormal);
        $this->assertTrue($cond->isSuitableVertex($anotherVertex)); // can handle
    }

    public function testConst()
    {
        $vertex = new Vertex(1, 1);
        $anotherVertex = new Vertex(2, 2);
        $passed = [$vertex->getId() => $vertex];
        $globalPassed = [$vertex->getId() => $vertex];
        $branchContext = new TraverseBranchContext(0, 0, $vertex);
        $context = new TraverseContext($anotherVertex, null, $branchContext, $passed, $globalPassed);

        $passCond = (new FilterCondition())->onlyVertexTypes([1]);
        $handleCond = (new VertexCondition());

        $filter = new ConstTraverseFilter($passCond, $handleCond);
        $this->assertTrue($filter->getPassCondition($context)->isSuitableVertex($vertex));
        $this->assertFalse($filter->getPassCondition($context)->isSuitableVertex($anotherVertex));
    }
}
