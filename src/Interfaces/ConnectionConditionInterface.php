<?php

namespace Smoren\GraphTools\Interfaces;

interface ConnectionConditionInterface
{
    /**
     * @param string $type
     * @return bool
     */
    public function hasConnectionType(string $type): bool;
}
