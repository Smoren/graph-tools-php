<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\EdgeInterface;

/**
 * Graph edge's class
 * @author Smoren <ofigate@gmail.com>
 */
class Edge implements EdgeInterface
{
    /**
     * @var non-empty-string edge's id
     */
    protected string $id;
    /**
     * @var non-empty-string edge's type
     */
    protected string $type;
    /**
     * @var non-empty-string start vertex's id of edge
     */
    protected string $fromId;
    /**
     * @var non-empty-string end vertex's id of edge
     */
    protected string $toId;
    /**
     * @var float edge's weight
     */
    protected float $weight;

    /**
     * Edge constructor
     * @param non-empty-string $id edge's id
     * @param non-empty-string $type edge's type
     * @param non-empty-string $fromId start vertex's id of edge
     * @param non-empty-string $toId end vertex's id of edge
     * @param float $weight edge's weight
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
