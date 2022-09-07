<?php

namespace Smoren\GraphTools\Tests\Unit\Models\Traits;

use Smoren\GraphTools\Conditions\FilterCondition;
use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Tests\Unit\Structs\VertexType;

/**
 * Trait OperatorTrait
 * @property string $id
 */
trait OperatorTrait
{
    public function isBranching(GraphRepositoryInterface $repo): bool
    {
        return (bool)$this->countOutput($repo);
    }

    public function isCollecting(GraphRepositoryInterface $repo): bool
    {
        return (bool)$this->countInput($repo);
    }

    public function countOutput(GraphRepositoryInterface $repo): int
    {
        /** @var VertexInterface|OperatorTrait $this */
        return count($repo->getNextVertexes($this, $this->_getFilterCondition()));
    }

    public function countInput(GraphRepositoryInterface $repo): int
    {
        /** @var VertexInterface|OperatorTrait $this */
        return count($repo->getPrevVertexes($this, $this->_getFilterCondition()));
    }

    protected function _getFilterCondition(): FilterConditionInterface
    {
        return (new FilterCondition())->onlyVertexTypes(
            VertexType::getWorkflowTypes()
        );
    }
}
