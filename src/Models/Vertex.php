<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Models\Interfaces\VertexInterface;

/**
 * Graph vertex's class
 * @author <ofigate@gmail.com> Smoren
 */
class Vertex implements VertexInterface
{
    /**
     * @var non-empty-string vertex's id
     */
    protected string $id;
    /**
     * @var non-empty-string vertex's type
     */
    protected string $type;
    /**
     * @var mixed custom extra data
     */
    protected $data;

    /**
     * Vertex constructor
     * @param non-empty-string $id vertex's id
     * @param non-empty-string $type vertex's type
     * @param mixed $data custom extra data
     */
    public function __construct(string $id, string $type, $data = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->data = $data;
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
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
