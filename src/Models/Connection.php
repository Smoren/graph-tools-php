<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Interfaces\ConnectionInterface;

class Connection implements ConnectionInterface
{
    /**
     * @var non-empty-string
     */
    protected string $id;
    /**
     * @var non-empty-string
     */
    protected string $type;
    /**
     * @var non-empty-string
     */
    protected string $fromId;
    /**
     * @var non-empty-string
     */
    protected string $toId;

    /**
     * @param non-empty-string $id
     * @param non-empty-string $type
     * @param non-empty-string $fromId
     * @param non-empty-string $toId
     */
    public function __construct(string $id, string $type, string $fromId, string $toId)
    {
        $this->id = $id;
        $this->type = $type;
        $this->fromId = $fromId;
        $this->toId = $toId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getFromId(): string
    {
        return $this->fromId;
    }

    public function getToId(): string
    {
        return $this->toId;
    }
}
