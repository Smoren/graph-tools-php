<?php

namespace Smoren\GraphTools\Helpers;

use Generator;
use Smoren\GraphTools\Models\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

class TraverseHelper
{
    /**
     * @param Generator $contexts
     * @param callable|null $callback
     * @return array<int, array<VertexInterface>>
     */
    public static function getBranches(Generator $contexts, ?callable $callback = null): array
    {
        $branchMap = [];
        /** @var TraverseContextInterface $context */
        foreach($contexts as $context) {
            if(is_callable($callback)) {
                $callback($context, $contexts);
            }

            $branchContext = $context->getBranchContext();
            $branchIndex = $branchContext->getIndex();
            $parentBranchIndex = $context->getBranchContext()->getParentIndex();
            $vertex = $context->getVertex();

            if(!isset($branchMap[$branchIndex])) {
                if(isset($branchMap[$parentBranchIndex])) {
                    $parentBranch = $branchMap[$parentBranchIndex];
                    $key = array_search($branchContext->getStart(), $parentBranch);
                    $branchMap[$branchIndex] = array_slice($parentBranch, 0, $key+1);
                } else {
                    $branchMap[$branchIndex] = [];
                }
            }

            $branchMap[$branchIndex][] = $vertex;
        }

        return $branchMap;
    }
}
