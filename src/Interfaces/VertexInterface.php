<?php

namespace Smoren\GraphTools\Interfaces;

interface VertexInterface
{
    /**
     * @return int|string
     */
    public function getId();

    /**
     * @return int|string
     */
    public function getType();
}
