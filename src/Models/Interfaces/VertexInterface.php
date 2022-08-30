<?php

namespace Smoren\GraphTools\Models\Interfaces;

/**
 * Graph vertex interface
 * @author <ofigate@gmail.com> Smoren
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
