<?php

namespace Smoren\GraphTools\Tests\Unit;

use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Conditions\VertexCondition;
use Smoren\GraphTools\Filters\ConstTraverseFilter;
use Smoren\GraphTools\Filters\TransparentTraverseFilter;
use Smoren\GraphTools\Models\Vertex;
use Smoren\GraphTools\Store\PreloadedGraphRepository;
use Smoren\GraphTools\Structs\FilterConfig;
use Smoren\GraphTools\Structs\TraverseBranchContext;
use Smoren\GraphTools\Structs\TraverseContext;

class FiltersTest extends \Codeception\Test\Unit
{
    public function testTransparent()
    {
        $vertex = new Vertex(1, 1);
        $anotherVertex = new Vertex(2, 2);
        $repo = new PreloadedGraphRepository([$vertex, $anotherVertex], []);

        $passed = [$vertex->getId() => $vertex];
        $globalPassed = [$vertex->getId() => $vertex];
        $branchContext = new TraverseBranchContext(0, 0, $vertex);
        $contextLoop = new TraverseContext($vertex, null, $repo, $branchContext, $passed, $globalPassed);
        $contextNormal = new TraverseContext($anotherVertex, null, $repo, $branchContext, $passed, $globalPassed);

        $filter = new TransparentTraverseFilter();
        $cond = $filter->getPassCondition($contextLoop);
        $this->assertTrue($cond->isSuitableVertex($vertex));                // can pass
        $this->assertTrue($cond->isSuitableVertex($anotherVertex));         // can pass
        $this->assertTrue($filter->matchesHandleCondition($contextLoop));   // can handle
        $this->assertTrue($filter->matchesHandleCondition($contextNormal)); // can handle

        $filter = new TransparentTraverseFilter([FilterConfig::PREVENT_REPEAT_HANDLE]);
        $cond = $filter->getPassCondition($contextLoop);
        $this->assertTrue($cond->isSuitableVertex($vertex));                // can pass
        $this->assertTrue($cond->isSuitableVertex($anotherVertex));         // can pass
        $this->assertFalse($filter->matchesHandleCondition($contextLoop));  // cannot handle twice
        $this->assertTrue($filter->matchesHandleCondition($contextNormal)); // can handle

        $filter = new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_HANDLE]);
        $cond = $filter->getPassCondition($contextLoop);
        $this->assertTrue($cond->isSuitableVertex($vertex));                // can pass
        $this->assertTrue($cond->isSuitableVertex($anotherVertex));         // can pass
        $this->assertFalse($filter->matchesHandleCondition($contextLoop));  // cannot handle when loop
        $this->assertTrue($filter->matchesHandleCondition($contextNormal)); // can handle without loop

        $filter = new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS]);
        $cond = $filter->getPassCondition($contextLoop);
        $this->assertFalse($cond->isSuitableVertex($vertex));                // cannot pass because loop in ctx
        $this->assertFalse($cond->isSuitableVertex($anotherVertex));         // cannot pass because loop in ctx
        $this->assertTrue($filter->matchesHandleCondition($contextLoop));    // can handle
        $this->assertTrue($filter->matchesHandleCondition($contextNormal));  // can handle

        $filter = new TransparentTraverseFilter([FilterConfig::PREVENT_RETURN_BACK_PASS]);
        $cond = $filter->getPassCondition($contextLoop);
        $this->assertFalse($cond->isSuitableVertex($vertex));               // cannot pass to return back
        $this->assertTrue($cond->isSuitableVertex($anotherVertex));         // can pass
        $cond = $filter->getPassCondition($contextNormal);
        $this->assertFalse($cond->isSuitableVertex($vertex));               // cannot pass to return back
        $this->assertTrue($cond->isSuitableVertex($anotherVertex));         // can pass
        $this->assertTrue($filter->matchesHandleCondition($contextLoop));   // can handle
        $this->assertTrue($filter->matchesHandleCondition($contextNormal)); // can handle
    }

    public function testConst()
    {
        $vertex = new Vertex(1, 1);
        $anotherVertex = new Vertex(2, 2);
        $repo = new PreloadedGraphRepository([$vertex, $anotherVertex], []);

        $passed = [$vertex->getId() => $vertex];
        $globalPassed = [$vertex->getId() => $vertex];
        $branchContext = new TraverseBranchContext(0, 0, $vertex);
        $context = new TraverseContext($anotherVertex, null, $repo, $branchContext, $passed, $globalPassed);

        $passCond = (new FilterCondition())->onlyVertexTypes([1]);
        $handleCond = (new VertexCondition());

        $filter = new ConstTraverseFilter($passCond, $handleCond);
        $this->assertTrue($filter->getPassCondition($context)->isSuitableVertex($vertex));
        $this->assertFalse($filter->getPassCondition($context)->isSuitableVertex($anotherVertex));
    }
}
