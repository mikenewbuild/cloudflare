<?php

namespace Mikenewbuild\Cloudflare;

class Config
{
    protected array $attributes;

    public function __construct()
    {
        $this->attributes = require __DIR__ . '/../config.php';
    }

    public function has(string $key)
    {
        return isset($this->attributes[$key]);
    }

    public function get(string $key)
    {
        return $this->attributes[$key];
    }
}
