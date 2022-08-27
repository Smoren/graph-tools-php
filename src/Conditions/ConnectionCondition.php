<?php

namespace Smoren\GraphTools\Conditions;

use Smoren\GraphTools\Conditions\Interfaces\ConnectionConditionInterface;
use Smoren\GraphTools\Conditions\Traits\ConnectionConditionTrait;

class ConnectionCondition implements ConnectionConditionInterface
{
    use ConnectionConditionTrait;

    /**
     * @var array<string>|null
     */
    protected ?array $connectionTypesOnly = null;
    /**
     * @var array<string>
     */
    protected array $connectionTypesExclude = [];
}
