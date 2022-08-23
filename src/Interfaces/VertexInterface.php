<?php

namespace Smoren\GraphTools\Interfaces;

interface VertexInterface
{
    /**
     * @return non-empty-string
     */
    public function getId(): string;

    /**
     * @return non-empty-string
     */
    public function getType(): string;
}
