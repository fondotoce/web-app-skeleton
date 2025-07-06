<?php

namespace App\Http\Exception;

use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends Exception implements ClientExceptionInterface
{
    public function __construct(
        string $message = '',
        int $code = Response::HTTP_UNAUTHORIZED,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getName(): string
    {
        return 'Unauthorized';
    }
}
