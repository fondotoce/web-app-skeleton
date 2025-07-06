<?php

namespace App\Http\Exception;

use App\Exception\UnprocessableEntityExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UnprocessableEntityException extends Exception implements ClientExceptionInterface
{
    public ConstraintViolationListInterface $violations;

    public function __construct(
        string $message = '',
        int $code = Response::HTTP_UNPROCESSABLE_ENTITY,
        ?UnprocessableEntityExceptionInterface $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getName(): string
    {
        return 'Unprocessable Entity';
    }
}
