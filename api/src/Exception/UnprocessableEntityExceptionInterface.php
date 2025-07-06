<?php

namespace App\Exception;

interface UnprocessableEntityExceptionInterface extends \Throwable
{
    /**
     * @return Error[]
     */
    public function getErrors(): array;
}
