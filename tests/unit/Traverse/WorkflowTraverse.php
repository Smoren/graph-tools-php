<?php

namespace Smoren\GraphTools\Tests\Unit\Traverse;

use Generator;
use Smoren\GraphTools\Filters\Interfaces\TraverseFilterInterface;
use Smoren\GraphTools\Models\Interfaces\VertexInterface;
use Smoren\GraphTools\Store\Interfaces\GraphRepositoryInterface;
use Smoren\GraphTools\Structs\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Tests\Unit\Models\FunctionVertex;
use Smoren\GraphTools\Tests\Unit\Models\OperatorAndVertex;
use Smoren\GraphTools\Tests\Unit\Models\OperatorXorVertex;
use Smoren\GraphTools\Tests\Unit\Structs\VertexType;
use Smoren\GraphTools\Tests\Unit\Traverse\Logic\OperatorAndLogic;
use Smoren\GraphTools\Tests\Unit\Traverse\Logic\OperatorXorLogic;
use Smoren\GraphTools\Traverse\Interfaces\TraverseInterface;
use Smoren\GraphTools\Traverse\Traverse;
use Smoren\GraphTools\Traverse\TraverseDirect;

class WorkflowTraverse implements TraverseInterface
{
    /**
     * @var TraverseDirect
     */
    protected TraverseDirect $traverse;
    /**
     * @var GraphRepositoryInterface
     */
    protected GraphRepositoryInterface $repository;
    /**
     * @var Generator<TraverseContextInterface>
     */
    protected Generator $contexts;
    /**
     * @var OperatorAndLogic
     */
    protected OperatorAndLogic $operatorAndLogic;
    /**
     * @var OperatorXorLogic
     */
    protected OperatorXorLogic $operatorXorLogic;

    public function __construct(GraphRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->traverse = new TraverseDirect($repository);
        $this->operatorAndLogic = new OperatorAndLogic();
        $this->operatorXorLogic = new OperatorXorLogic();
    }

    /**
     * @param VertexInterface $start
     * @param TraverseFilterInterface $filter
     * @param int $traverseMode
     * @return Generator<TraverseContextInterface>
     */
    public function generate(
        VertexInterface $start,
        TraverseFilterInterface $filter,
        int $traverseMode = Traverse::MODE_WIDE
    ): Generator {
        $this->contexts = $this->traverse->generate($start, $filter, $traverseMode);
        /** @var TraverseContextInterface $context */
        foreach($this->contexts as $context) {
            $vertex = $context->getVertex();
            switch($vertex->getType()) {
                case VertexType::EVENT:
                    yield from $this->handleEvent($context);
                    break;
                case VertexType::FUNCTION:
                    yield from $this->handleFunction($context);
                    break;
                case VertexType::OPERATOR_AND:
                    yield from $this->handleOperatorAnd($context);
                    break;
                case VertexType::OPERATOR_XOR:
                    yield from $this->handleOperatorXor($context);
                    break;
            }
        }
    }

    /**
     * @param TraverseContextInterface $context
     * @return Generator<TraverseContextInterface>
     */
    protected function handleEvent(TraverseContextInterface $context): Generator
    {
        yield $context;
    }

    /**
     * @param TraverseContextInterface $context
     * @return Generator<TraverseContextInterface>
     */
    protected function handleFunction(TraverseContextInterface $context): Generator
    {
        /** @var FunctionVertex $func */
        $func = $context->getVertex();
        $this->operatorXorLogic->registerFunction($func, $this->repository);
        yield $context;
    }

    /**
     * @param TraverseContextInterface $context
     * @return Generator<TraverseContextInterface>
     */
    protected function handleOperatorAnd(TraverseContextInterface $context): Generator
    {
        /** @var OperatorAndVertex $operator */
        $operator = $context->getVertex();
        $this->operatorAndLogic->registerPass($operator);

        if(!$this->operatorAndLogic->canPass($operator, $this->repository)) {
            $this->contexts->send(Traverse::STOP_BRANCH);
        }

        yield from [];
    }

    /**
     * @param TraverseContextInterface $context
     * @return Generator<TraverseContextInterface>
     */
    protected function handleOperatorXor(TraverseContextInterface $context): Generator
    {
        /** @var OperatorXorVertex $operator */
        $operator = $context->getVertex();
        if(($passCond = $this->operatorXorLogic->getPassFilter($operator)) !== null) {
            $this->contexts->send($passCond);
        }
        yield from [];
    }
}
