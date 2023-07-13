<?php

namespace LogadApp\Router;

final class Route
{
    private string $path;
    private string $method;
    private array $args;

    public function setPath(string $path):self
    {
        $this->path = $path;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setArgs(array $args): self
    {
        $this->args = $args;
        return $this;
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
