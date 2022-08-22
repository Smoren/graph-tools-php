<?php

namespace Smoren\GraphTools\Interfaces;

interface VertexInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getType(): string;
}
