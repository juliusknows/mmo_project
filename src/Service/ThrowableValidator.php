<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class ThrowableValidator
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    /**
     * @param array<string>|string|null $groups
     */
    public function validate(mixed $value, mixed $constraints = null, array|string|null $groups = null): void
    {
        $violations = $this->validator->validate($value, $constraints, $groups);

        if (0 !== $violations->count()) {
            throw new ValidationFailedException($value, $violations);
        }
    }
}
