<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\ConnectionInterface;

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

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getFromId(): string
    {
        return $this->fromId;
    }

    /**
     * @inheritDoc
     */
    public function getToId(): string
    {
        return $this->toId;
    }
}
