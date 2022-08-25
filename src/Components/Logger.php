<?php

namespace Smoren\GraphTools\Components;

class Logger
{
    /**
     * @var array<string>
     */
    protected array $data = [];

    public function log(string $message): self
    {
        $this->data[] = $message;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function get(): array
    {
        return $this->data;
    }
}
