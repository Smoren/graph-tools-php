<?php

namespace Smoren\GraphTools\Conditions;

use Smoren\GraphTools\Conditions\Interfaces\EdgeConditionInterface;
use Smoren\GraphTools\Conditions\Traits\EdgeConditionTrait;

class EdgeCondition implements EdgeConditionInterface
{
    use EdgeConditionTrait;

    /**
     * @var array<string>|null
     */
    protected ?array $edgeTypesOnly = null;
    /**
     * @var array<string>
     */
    protected array $edgeTypesExclude = [];
}
