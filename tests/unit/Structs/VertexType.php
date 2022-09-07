<?php

namespace Smoren\GraphTools\Tests\Unit\Structs;

class VertexType
{
    public const EVENT = 1;
    public const FUNCTION = 2;
    public const OPERATOR_AND = 3;
    public const OPERATOR_XOR = 4;

    /**
     * @return int[]
     */
    public static function getWorkflowTypes(): array
    {
        return [
            static::EVENT,
            static::FUNCTION,
            static::OPERATOR_AND,
            static::OPERATOR_XOR,
        ];
    }
}
