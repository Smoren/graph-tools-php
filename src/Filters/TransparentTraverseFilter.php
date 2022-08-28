<?php

namespace Smoren\GraphTools\Filters;

use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;

class TransparentTraverseFilter extends ConstTraverseFilter implements TraverseFilterInterface
{
    public function __construct(bool $preventLoops = true)
    {
        parent::__construct(null, null, $preventLoops);
    }
}
