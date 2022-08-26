<?php

namespace Smoren\GraphTools\Components;

use Smoren\GraphTools\Exceptions\TraverseException;
use Smoren\GraphTools\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Interfaces\TraverseContextInterface;
use Smoren\GraphTools\Interfaces\TraverseHandlerInterface;

class TraverseLogger implements TraverseHandlerInterface
{
    /**
     * @var array<TraverseContextInterface>
     */
    protected array $log = [];

    public function handle(TraverseContextInterface $context): FilterConditionInterface
    {
        $this->log[] = $context;

        if($context->getIsLoop()) {
            throw new TraverseException('loop detected', TraverseException::STOP_TRAVERSE);
        }

        // TODO собственный метод в этом классе?
        return $context->getFilterCondition();
    }

    /**
     * @return array<TraverseContextInterface>
     */
    public function getLog(): array
    {
        return $this->log;
    }

    /**
     * @return array<string>
     */
    public function getStringsLog(): array
    {
        $result = [];

        foreach($this->log as $context) {
            $item = "[BRANCH #{$context->getBranchIndex()}] [VERTEX #{$context->getVertex()->getId()}] ";
            if($context->getIsLoop()) {
                $item .= '[LOOP]';
            }
            $result[] = $item;
        }

        return $result;
    }
}