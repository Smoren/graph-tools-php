<?php

namespace Smoren\GraphTools\Conditions;

use Smoren\GraphTools\Conditions\Interfaces\FilterConditionInterface;
use Smoren\GraphTools\Conditions\Traits\EdgeConditionTrait;
use Smoren\GraphTools\Conditions\Traits\VertexConditionTrait;

/**
 * implementation of filter condition
 * @author <ofigate@gmail.com> Smoren
 */
class FilterCondition implements FilterConditionInterface
{
    use VertexConditionTrait;
    use EdgeConditionTrait;

    /**
     * @var array<string>|null vertex types whitelist
     */
    protected ?array $vertexTypesOnly = null;
    /**
     * @var array<string> vertex types blacklist
     */
    protected array $vertexTypesExclude = [];
    /**
     * @var array<string>|null vertex ids whitelist
     */
    protected ?array $vertexIdsOnly = null;
    /**
     * @var array<string> vertex ids blacklist
     */
    protected array $vertexIdsExclude = [];
    /**
     * @var array<string>|null edge types whitelist
     */
    protected ?array $edgeTypesOnly = null;
    /**
     * @var array<string> edge types blacklist
     */
    protected array $edgeTypesExclude = [];
}
