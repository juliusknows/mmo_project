<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Manager\NewsManager;
use App\Request\NewsPublicationRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final readonly class NewsCrudController
{
    public function __construct(
        private Environment $twig,
        private NewsManager $newsManager,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function new(): Response
    {
        return new Response($this->twig->render('admin/news/new.html.twig'));
    }

    public function show(int $id): Response
    {
        $news = $this->newsManager->findById($id);

        return new Response($this->twig->render('admin/news/show.html.twig', [
            'news' => $news,
        ]));
    }

    public function showAll(): Response
    {
        $allNews = $this->newsManager->getAll();

        return new Response($this->twig->render('admin/news/all.html.twig', [
            'allNews' => $allNews,
        ]));
    }

    public function create(NewsPublicationRequest $request): RedirectResponse
    {
        $this->newsManager->create($request->getNews());

        return new RedirectResponse($this->urlGenerator->generate('admin_news_all'));
    }

    public function edit(int $id): Response
    {
        $news = $this->newsManager->findById($id);

        if (null !== $news) {
            return new Response($this->twig->render('admin/news/edit.html.twig', [
                'news' => $news,
            ]));
        }

        return new RedirectResponse($this->urlGenerator->generate('admin_news_all'));
    }

    public function update(int $id, NewsPublicationRequest $request): RedirectResponse
    {
        $this->newsManager->refresh($id, $request->getNews());

        return new RedirectResponse($this->urlGenerator->generate('admin_news_all'));
    }

    public function delete(int $id): RedirectResponse
    {
        $this->newsManager->remove($id);

        return new RedirectResponse($this->urlGenerator->generate('admin_news_all'));
    }
}
