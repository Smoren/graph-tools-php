<?php

namespace Smoren\GraphTools\Interfaces;

interface ConnectionConditionInterface
{
    /**
     * @return array<string>|null
     */
    public function getConnectionTypesOnly(): ?array;

    /**
     * @return array<string>
     */
    public function getConnectionTypesExclude(): array;
}
