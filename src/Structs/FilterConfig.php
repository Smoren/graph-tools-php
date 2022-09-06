<?php

namespace Smoren\GraphTools\Structs;

/**
 * Filter config implementation
 * @author Smoren <ofigate@gmail.com>
 */
class FilterConfig
{
    public const PREVENT_LOOP_PASS = 1;
    public const PREVENT_LOOP_HANDLE = 2;
    public const PREVENT_RETURN_BACK_PASS = 3;
    public const PREVENT_REPEAT_HANDLE = 4;

    /**
     * @var array<int> config storage
     */
    protected array $config;

    /**
     * FilterConfig constructor
     * @param array<int> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function has(int $flag): bool
    {
        return in_array($flag, $this->config);
    }
}
