<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;

class Edge implements EdgeInterface
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
     * @var float
     */
    protected float $weight;

    /**
     * @param non-empty-string $id
     * @param non-empty-string $type
     * @param non-empty-string $fromId
     * @param non-empty-string $toId
     */
    public function __construct(string $id, string $type, string $fromId, string $toId, float $weight = 1)
    {
        $this->id = $id;
        $this->type = $type;
        $this->fromId = $fromId;
        $this->toId = $toId;
        $this->weight = $weight;
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

    /**
     * @inheritDoc
     */
    public function getWeight(): float
    {
        return $this->weight;
    }
}
