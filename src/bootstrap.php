<?php

require_once __DIR__ . '/../vendor/autoload.php';

function dd($value)
{
    var_dump($value);
    die;
}

function config(string $key)
{
    $config = new Mikenewbuild\Cloudflare\Config;

    if (!$config->has($key)) {
        throw new \OutOfBoundsException("Config is missing the key: '{$key}'.");
    }

    return $config->get($key);
}
