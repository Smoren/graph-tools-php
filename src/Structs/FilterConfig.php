<?php

namespace Smoren\GraphTools\Structs;

class FilterConfig
{
    public const PREVENT_LOOP_PASS = 1;
    public const PREVENT_LOOP_HANDLE = 2;
    public const PREVENT_RETURN_BACK = 3;

    /**
     * @var array<int>
     */
    protected array $config;

    /**
     * @param array<int> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @param int $flag
     * @return bool
     */
    public function isOn(int $flag): bool
    {
        return in_array($flag, $this->config);
    }
}