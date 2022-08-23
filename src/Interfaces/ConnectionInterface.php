<?php

namespace Smoren\GraphTools\Interfaces;

interface ConnectionInterface
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
