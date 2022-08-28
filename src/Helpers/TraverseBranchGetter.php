<?php

namespace Smoren\GraphTools\Helpers;

use Generator;
use Smoren\GraphTools\Models\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;

class TraverseBranchGetter
{
    /**
     * @param Generator<TraverseContextInterface> $contexts
     * @return array<int, array<VertexInterface>>
     */
    public static function getMap(Generator $contexts): array
    {
        $branchMap = [];
        /** @var TraverseContextInterface $context */
        foreach($contexts as $context) {
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
