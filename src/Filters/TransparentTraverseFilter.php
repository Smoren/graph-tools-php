<?php

namespace Smoren\GraphTools\Filters;

use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;

/**
 * Transparent traverse filter
 * @author Smoren <ofigate@gmail.com>
 */
class TransparentTraverseFilter extends ConstTraverseFilter implements TraverseFilterInterface
{
    /**
     * @param array<int> $config filter config
     */
    public function __construct(array $config = [])
    {
        parent::__construct(null, null, $config);
    }
}
