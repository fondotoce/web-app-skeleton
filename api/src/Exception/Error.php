<?php

namespace App\Exception;

class Error
{
    public string $field;
    public string $message;

    public function __construct(string $field, string|\Stringable $message)
    {
        $this->field = $field;
        $this->message = $message;
    }
}
