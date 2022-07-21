<?php

namespace Mikenewbuild\Cloudflare;

use stdClass;

class Response
{
    protected stdClass $attributes;

    public function __construct(string|stdClass $attributes)
    {
        $this->attributes = is_string($attributes) ? json_decode($attributes) : $attributes;
    }

    public function __get(string $key)
    {
        return $this->attributes->{$key};
    }

    public function messages(): string
    {
        $result = '';

        if (!$this->success) {
            foreach ($this->errors as $error) {
                $result .= $error->message . PHP_EOL;
            }
        } else {
            foreach ($this->messages as $message) {
                $result .= $message->message . PHP_EOL;
            }
        }

        return $result;
    }
}
