<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\User;
use App\Exception\RequestValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final readonly class EntityRegistrator
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ThrowableValidator $validator,
    ) {
    }

    public function registerUser(string $email, string $password): void
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);

        try {
            $this->validator->validate($user);
        } catch (ValidationFailedException $exception) {
            $violations = $exception->getViolations();

            throw new RequestValidationException($violations);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
