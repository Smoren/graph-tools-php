<?php

namespace Smoren\GraphTools\Tests\Unit\Traverse\Logic;

use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Tests\Unit\Models\OperatorAndVertex;

class OperatorAndLogic
{
    protected array $passCountMap = [];

    public function registerPass(OperatorAndVertex $vertex): self
    {
        $vertexId = $vertex->getId();
        if(!isset($this->passCountMap[$vertexId])) {
            $this->passCountMap[$vertexId] = 0;
        }
        $this->passCountMap[$vertexId]++;

        return $this;
    }

    public function canPass(OperatorAndVertex $vertex, GraphRepositoryInterface $repository): bool
    {
        return $this->getPassCount($vertex) === $vertex->countInput($repository);
    }

    protected function getPassCount(OperatorAndVertex $vertex): int
    {
        return $this->passCountMap[$vertex->getId()] ?? 0;
    }
}
