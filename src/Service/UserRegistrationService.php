<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\UserRegistrationRequest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function count;

final readonly class UserRegistrationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * @return array{
     *     success: bool,
     *     message: string,
     * }
     */
    public function register(UserRegistrationRequest $dto): array
    {
        $user = new User();
        $user->setEmail($dto->email);
        $user->setPassword($dto->password);
        $user->setPasswordRepeat($dto->passwordRepeat);

        $violations = $this->validator->validate($user, null, ['email', 'password', 'passwordRepeat']);

        if (count($violations) > 0) {
            return [
                'success' => false,
                'message' => (string) $violations->get(0)->getMessage(),
            ];
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return [
            'success' => true,
            'message' => 'Пользователь успешно зарегистрирован',
        ];
    }
}
