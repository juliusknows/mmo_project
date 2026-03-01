<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UserManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function register(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getTotal(): int
    {
        return $this->entityManager->getRepository(User::class)->count([]);
    }
}
