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

    /**
     * @param array<string>|null $types
     * @return ConnectionConditionInterface
     */
    public function setConnectionTypesOnly(?array $types): ConnectionConditionInterface;

    /**
     * @param array<string> $types
     * @return ConnectionConditionInterface
     */
    public function setConnectionTypesExclude(array $types): ConnectionConditionInterface;
}
