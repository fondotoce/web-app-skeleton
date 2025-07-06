<?php

namespace App\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

final class Validator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(object $object, array $constraints = null, array $groups = null): void
    {
        $violations = $this->validator->validate($object, $constraints, $groups);

        if ($violations->count() > 0) {
            throw new ValidatorException($violations);
        }
    }
}
