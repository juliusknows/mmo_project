<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;

final readonly class UserManager
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function create(User $user): void
    {
        $this->userRepository->createUser($user);
    }

    public function getTotal(): int
    {
        return $this->userRepository->count();
    }
}
