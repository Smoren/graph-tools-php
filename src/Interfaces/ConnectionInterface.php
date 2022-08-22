<?php

namespace Smoren\GraphTools\Interfaces;

interface ConnectionInterface
{
    /**
     * @return int|string
     */
    public function getId();

    /**
     * @return int|string
     */
    public function getType();

    /**
     * @return int|string
     */
    public function getFrom();

    /**
     * @return int|string
     */
    public function getTo();
}
