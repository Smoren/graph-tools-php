<?php

namespace Smoren\GraphTools\Helpers;

use Generator;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;

/**
 * Helper for getting traversing results
 * @author Smoren <ofigate@gmail.com>
 */
class TraverseHelper
{
    /**
     * Returns branches of traversing (every branch as array of Vertex objects)
     * @param Generator<TraverseContextInterface> $contexts traverse contexts' iterator
     * @param callable|null $callback action to do for each context
     * @return array<int, array<VertexInterface>> branch list
     */
    public static function getBranches(Generator $contexts, ?callable $callback = null): array
    {
        $branchMap = [];
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
