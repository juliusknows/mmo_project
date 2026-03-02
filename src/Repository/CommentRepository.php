<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
final class CommentRepository extends ServiceEntityRepository
{
    public const COMMENTS_PER_PAGE = 2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function createComment(Comment $comment): void
    {
        $this->getEntityManager()->persist($comment);
        $this->getEntityManager()->flush();
    }

    public function removeComment(Comment $comment): void
    {
        $this->getEntityManager()->remove($comment);
        $this->getEntityManager()->flush();
    }

    public function edit(): void
    {
        $this->getEntityManager()->flush();
    }

    /**
     * Получает пагинатор для комментариев конкретной новости.
     *
     * @param News $news   Конкретная новость
     * @param int  $offset Смещение (начальная позиция)
     *
     * @return Paginator<Comment> Пагинатор с комментариями
     */
    public function getCommentPaginator(News $news, int $offset): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('comment')
            ->andWhere('comment.news = :news')
            ->setParameter('news', $news)
            ->setMaxResults(self::COMMENTS_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();

        return new Paginator($queryBuilder);
    }
}
