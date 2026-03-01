<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CommentManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function add(Comment $comment): void
    {
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    public function findNewsById(int $id): ?News
    {
        return $this->entityManager->getRepository(News::class)->find($id);
    }

    public function findById(int $id): ?Comment
    {
        return $this->entityManager->getRepository(Comment::class)->find($id);
    }

    public function remove(int $id): void
    {
        $comment = $this->findById($id);

        if (null !== $comment) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
        }
    }

    public function getTotal(): int
    {
        return $this->entityManager->getRepository(Comment::class)->count([]);
    }

    public function refresh(int $id, Comment $comment): void
    {
        $olDcomment = $this->entityManager->getRepository(Comment::class)->find($id);

        $olDcomment->setAuthor($comment->getAuthor());
        $olDcomment->setText($comment->getText());
        $this->entityManager->flush();
    }
}
