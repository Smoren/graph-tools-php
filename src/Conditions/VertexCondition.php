<?php

namespace Smoren\GraphTools\Conditions;

use Smoren\GraphTools\Conditions\Interfaces\VertexConditionInterface;
use Smoren\GraphTools\Conditions\Traits\VertexConditionTrait;

class VertexCondition implements VertexConditionInterface
{
    use VertexConditionTrait;

    /**
     * @var array<string>|null
     */
    protected ?array $vertexTypesOnly = null;
    /**
     * @var array<string>
     */
    protected array $vertexTypesExclude = [];
}
