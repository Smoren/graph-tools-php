<?php

namespace Smoren\GraphTools\Interfaces;

interface VertexConditionInterface
{
    /**
     * @param string $type
     * @return bool
     */
    public function hasVertexType(string $type): bool;
}
