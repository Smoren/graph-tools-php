<?php

namespace Smoren\GraphTools\Interfaces;

interface VertexConditionInterface
{
    /**
     * @param int|string $type
     * @return bool
     */
    public function hasVertexType($type): bool;
}
