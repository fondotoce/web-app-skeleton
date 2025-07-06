<?php

namespace App\Http\Exception;

use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends Exception implements ClientExceptionInterface
{
    public function __construct(
        string $message = '',
        int $code = Response::HTTP_NOT_FOUND,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getName(): string
    {
        return 'Not Found';
    }
}
