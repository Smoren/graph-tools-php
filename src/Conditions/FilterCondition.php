<?php

namespace Smoren\GraphTools\Conditions;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Traits\EdgeConditionTrait;
use Smoren\GraphTools\Conditions\Traits\VertexConditionTrait;

class FilterCondition implements FilterConditionInterface
{
    use VertexConditionTrait;
    use EdgeConditionTrait;

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
    protected ?array $vertexIdsOnly = null;
    /**
     * @var array<string>
     */
    protected array $vertexIdsExclude = [];
    /**
     * @var array<string>|null
     */
    protected ?array $edgeTypesOnly = null;
    /**
     * @var array<string>
     */
    protected array $edgeTypesExclude = [];
}
