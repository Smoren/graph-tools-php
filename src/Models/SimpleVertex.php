<?php

namespace Smoren\GraphTools\Models;

use Smoren\GraphTools\Interfaces\VertexInterface;

class SimpleVertex implements VertexInterface
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
     * @var mixed
     */
    protected $data;

    /**
     * @param non-empty-string $id
     * @param non-empty-string $type
     * @param mixed $data
     */
    public function __construct(string $id, string $type, $data)
    {
        $this->id = $id;
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * @return non-empty-string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return non-empty-string
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