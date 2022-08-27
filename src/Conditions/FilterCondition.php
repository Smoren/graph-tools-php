<?php

namespace Smoren\GraphTools\Conditions;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Traits\ConnectionConditionTrait;
use Smoren\GraphTools\Conditions\Traits\VertexConditionTrait;

class FilterCondition implements FilterConditionInterface
{
    use VertexConditionTrait;
    use ConnectionConditionTrait;

    /**
     * @var array<string>|null
     */
    protected ?array $vertexTypesOnly = null;
    /**
     * @var array<string>
     */
    protected array $vertexTypesExclude = [];
    /**
     * @var array<string>|null
     */
    protected ?array $connectionTypesOnly = null;
    /**
     * @var array<string>
     */
    protected array $connectionTypesExclude = [];
}
