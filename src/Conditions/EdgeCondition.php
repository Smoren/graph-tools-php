<?php

namespace Smoren\GraphTools\Conditions;

use Smoren\GraphTools\Conditions\Interfaces\EdgeConditionInterface;
use Smoren\GraphTools\Conditions\Traits\EdgeConditionTrait;

/**
 * implementation of edge condition
 * @author <ofigate@gmail.com> Smoren
 */
class EdgeCondition implements EdgeConditionInterface
{
    use EdgeConditionTrait;

    /**
     * @var array<string>|null edge types whitelist
     */
    protected ?array $edgeTypesOnly = null;
    /**
     * @var array<string> edge types blacklist
     */
    protected array $edgeTypesExclude = [];
}
