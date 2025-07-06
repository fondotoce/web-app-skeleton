<?php

namespace App\Http\Exception;

abstract class Exception extends \RuntimeException
{
    public function getName(): string
    {
        return 'Exception';
    }
}
