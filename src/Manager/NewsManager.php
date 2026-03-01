<?php

/** @noinspection PhpRedundantOptionalArgumentInspection */

declare(strict_types=1);

namespace App\Manager;

use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;

final readonly class NewsManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(News $news): void
    {
        $this->entityManager->persist($news);
        $this->entityManager->flush();
    }

    public function refresh(int $id, News $news): void
    {
        $oldNews = $this->findById($id);

        if (null !== $oldNews) {
            $title = $news->getTitle();

            if (null !== $title) {
                $oldNews->setTitle($title);
            }
            $summary = $news->getSummary();

            if (null !== $summary) {
                $oldNews->setSummary($summary);
            }
            $content = $news->getContent();

            if (null !== $content) {
                $oldNews->setContent($content);
            }
            $this->entityManager->flush();
        }
    }

    public function remove(int $id): void
    {
        $news = $this->findById($id);

        if (null !== $news) {
            $this->entityManager->remove($news);
            $this->entityManager->flush();
        }
    }

    /**
     * @return array<int, News> Массив новостей, индексированный по целочисленному ключу
     */
    public function getAll(): array
    {
        return $this->entityManager->getRepository(News::class)->findAll();
    }

    public function findById(int $id): ?News
    {
        return $this->entityManager->getRepository(News::class)->find($id);
    }

    public function getTotal(): int
    {
        return $this->entityManager->getRepository(News::class)->count([]);
    }

    /**
     * @return array<int, News>
     */
    public function getLatestNews(int $limit = 2): array
    {
        return $this->entityManager->getRepository(News::class)
            ->findBy([], ['id' => 'DESC'], $limit);
    }
}
