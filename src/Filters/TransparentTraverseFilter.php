<?php

namespace Smoren\GraphTools\Filters;

use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;

class TransparentTraverseFilter extends ConstTraverseFilter implements TraverseFilterInterface
{
    public function __construct(bool $preventLoopContinue = true, bool $preventReturnBack = false)
    {
        parent::__construct(null, null, $preventLoopContinue, $preventReturnBack);
    }
}
