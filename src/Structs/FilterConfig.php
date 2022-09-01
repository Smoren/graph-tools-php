<?php

namespace Smoren\GraphTools\Structs;

use Smoren\GraphTools\Structs\Interfaces\FilterConfigInterface;

/**
 * Filter config implementation
 * @author <ofigate@gmail.com> Smoren
 */
class FilterConfig implements FilterConfigInterface
{
    public const PREVENT_LOOP_PASS = 1;
    public const PREVENT_LOOP_HANDLE = 2;
    public const PREVENT_RETURN_BACK = 3;
    public const HANDLE_UNIQUE_VERTEXES = 4;

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
