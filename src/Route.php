<?php

namespace LogadApp\Router;

final class Route
{
    private string $path;
    private string $method;
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

}
