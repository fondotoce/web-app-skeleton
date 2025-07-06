<?php

namespace App\Http\Exception;

use Symfony\Component\HttpFoundation\Response;

class ServerErrorException extends Exception implements ClientExceptionInterface
{
    public function __construct(
        string $message = '',
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getName(): string
    {
        return 'Internal Server Error';
    }
}
