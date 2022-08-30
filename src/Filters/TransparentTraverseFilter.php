<?php

namespace Smoren\GraphTools\Filters;

use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;

class TransparentTraverseFilter extends ConstTraverseFilter implements TraverseFilterInterface
{
    // bool $preventLoopContinue = true, bool $preventReturnBack = false
    /**
     * @param array<int> $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct(null, null, $config);
    }
}
