<?php

namespace Smoren\GraphTools\Interfaces;

interface VertexConditionInterface
{
    /**
     * @return array<string>|null
     */
    public function getVertexTypesOnly(): ?array;

    /**
     * @return array<string>
     */
    public function getVertexTypesExclude(): array;
}
