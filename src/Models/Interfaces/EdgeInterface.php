<?php

namespace Smoren\GraphTools\Models\Interfaces;

/**
 * Graph edge interface
 * @author Smoren <ofigate@gmail.com>
 */
interface EdgeInterface
{
    /**
     * Returns edge's id
     * @return non-empty-string
     */
    public function getId(): string;

    /**
     * Returns edge's type
     * @return non-empty-string
     */
    public function getType(): string;

    /**
     * Returns start vertex's id of edge
     * @return non-empty-string
     */
    public function getFromId(): string;

    /**
     * Returns end vertex's id of edge
     * @return non-empty-string
     */
    public function getToId(): string;

    /**
     * Returns edge's weight
     * @return float
     */
    public function getWeight(): float;
}
