<?php

/** @noinspection PhpRedundantOptionalArgumentInspection */

declare(strict_types=1);

namespace App\Manager;

use App\Entity\News;
use App\Repository\NewsRepository;
use App\Request\NewsPublicationRequest;

final readonly class NewsManager
{
    public function __construct(
        private NewsRepository $newsRepository,
    ) {
    }

    public function create(NewsPublicationRequest $request): News
    {
        $news = new News();
        $news->setTitle($request->getTitle());
        $news->setSummary($request->getSummary());
        $news->setContent($request->getContent());

        $this->newsRepository->createNews($news);

        return $news;
    }

    public function edit(News $news, NewsPublicationRequest $request): void
    {
        $news->setTitle($request->getTitle());
        $news->setSummary($request->getSummary());
        $news->setContent($request->getContent());

        $this->newsRepository->editNews();
    }

    public function remove(News $news): void
    {
        $this->newsRepository->removeNews($news);
    }

    /**
     * @return array<int, News> Массив новостей, индексированный по целочисленному ключу
     */
    public function getAll(): array
    {
        return $this->newsRepository->findAll();
    }

    public function getTotal(): int
    {
        return $this->newsRepository->count();
    }

    /**
     * @return array<int, News>
     */
    public function getLatestNews(int $limit = 2): array
    {
        return $this->newsRepository->findBy([], ['id' => 'DESC'], $limit);
    }
}
