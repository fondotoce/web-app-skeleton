<?php

namespace App\Http\Exception;

use Symfony\Component\HttpFoundation\Response;

class BadRequestException extends Exception implements ClientExceptionInterface
{
    public function __construct(
        string $message = '',
        int $code = Response::HTTP_BAD_REQUEST,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getName(): string
    {
        return 'Bad Request';
    }
}
