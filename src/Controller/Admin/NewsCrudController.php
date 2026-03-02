<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\News;
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

    public function createForm(): Response
    {
        return new Response($this->twig->render('admin/news/newsForm.html.twig'));
    }

    public function show(News $news): Response
    {
        return new Response($this->twig->render('admin/news/showNews.html.twig', [
            'news' => $news,
        ]));
    }

    public function list(): Response
    {
        $allNews = $this->newsManager->getAll();

        return new Response($this->twig->render('admin/news/list.html.twig', [
            'allNews' => $allNews,
        ]));
    }

    public function create(NewsPublicationRequest $request): RedirectResponse
    {
        $news = $this->newsManager->create($request);

        return new RedirectResponse($this->urlGenerator->generate('admin_news_show', ['id' => $news->getId()]));
    }

    public function createEditForm(News $news): Response
    {
        return new Response($this->twig->render('admin/news/editForm.html.twig', [
            'news' => $news,
        ]));
    }

    public function edit(News $news, NewsPublicationRequest $request): RedirectResponse
    {
        $this->newsManager->edit($news, $request);

        return new RedirectResponse($this->urlGenerator->generate('admin_news_show', ['id' => $news->getId()]));
    }

    public function delete(News $news): RedirectResponse
    {
        $this->newsManager->remove($news);

        return new RedirectResponse($this->urlGenerator->generate('admin_news_list'));
    }
}
