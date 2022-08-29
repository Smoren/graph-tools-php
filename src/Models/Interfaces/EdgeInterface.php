<?php

namespace Smoren\GraphTools\Models\Interfaces;

interface EdgeInterface
{
    /**
     * @return non-empty-string
     */
    public function getId(): string;

    /**
     * @return non-empty-string
     */
    public function getType(): string;

    /**
     * @return non-empty-string
     */
    public function getFromId(): string;

    /**
     * @return non-empty-string
     */
    public function getToId(): string;
}
