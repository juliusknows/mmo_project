<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final class TableClearController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function clearUserEntityTable(): Response
    {
        $repository = $this->entityManager->getRepository(User::class);
        $items = $repository->findAll();

        foreach ($items as $item) {
            $this->entityManager->remove($item);
        }

        $this->entityManager->flush();

        return new Response('Таблица очищена!');
    }
}
