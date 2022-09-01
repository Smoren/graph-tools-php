<?php

namespace Smoren\GraphTools\Structs\Interfaces;

/**
 * Filter config interface
 * @author <ofigate@gmail.com> Smoren
 */
interface FilterConfigInterface
{
    /**
     * Returns true if flag is on in config
     * @param int $flag flag
     * @return bool
     */
    public function has(int $flag): bool;
}
