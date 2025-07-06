<?php

namespace App\Validator;

use App\Exception\Error;
use App\Exception\UnprocessableEntityExceptionInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidatorException extends \LogicException implements UnprocessableEntityExceptionInterface
{
    private ConstraintViolationListInterface $violations;
    private array $errors = [];

    public function __construct(
        ConstraintViolationListInterface $violations,
        string $message = 'Invalid input.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $this->violations = $violations;
        parent::__construct($message, $code, $previous);
    }

    public function getName(): string
    {
        return 'Unprocessable Entity';
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public function getErrors(): array
    {
        if (!$this->violations->count()) {
            return [];
        }

        foreach ($this->violations as $violation) {
            /** @var ConstraintViolationInterface $violation */
            $this->errors[] = new Error($violation->getPropertyPath(), $violation->getMessage());
        }

        return $this->errors;
    }
}
