<?php

namespace Smoren\GraphTools\Models\Interfaces;

/**
 * Graph vertex interface
 * @author Smoren <ofigate@gmail.com>
 */
interface VertexInterface
{
    /**
     * Return vertex's id
     * @return non-empty-string
     */
    public function getId(): string;

    /**
     * Return vertex's type
     * @return non-empty-string
     */
    public function getType(): string;
}
