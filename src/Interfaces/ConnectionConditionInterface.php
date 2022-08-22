<?php

namespace Smoren\GraphTools\Interfaces;

interface ConnectionConditionInterface
{
    /**
     * @param int|string $type
     * @return bool
     */
    public function hasConnectionType($type): bool;
}
