<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Manager\CommentManager;
use App\Request\CommentPublicationRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final readonly class CommentCrudController
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private CommentManager $commentManager,
        private Environment $twig,
    ) {
    }

    public function create(CommentPublicationRequest $request): RedirectResponse
    {
        $news = $this->commentManager->findNewsById($request->getNewsId());

        if (null !== $news) {
            $comment = $request->getComment()->setNews($news);
            $this->commentManager->add($comment);

            return new RedirectResponse($this->urlGenerator->generate('admin_news_show', ['id' => $news->getId()]));
        }

        return new RedirectResponse($this->urlGenerator->generate('admin_news_all'));
    }

    public function delete(int $id): RedirectResponse
    {
        $comment = $this->commentManager->findById($id);

        if (null !== $comment) {
            $news = $comment->getNews();
            $this->commentManager->remove($id);

            return new RedirectResponse($this->urlGenerator->generate('admin_news_show', ['id' => $news->getId()]));
        }

        return new RedirectResponse($this->urlGenerator->generate('admin_news_all'));
    }

    public function edit(int $id): Response
    {
        $comment = $this->commentManager->findById($id);

        return new Response($this->twig->render('admin/news/editComment.html.twig', [
            'comment' => $comment,
        ]));
    }

    public function update(int $id, CommentPublicationRequest $request): RedirectResponse
    {
        $news = $request->getNewsId();
        $this->commentManager->refresh($id, $request->getComment());

        return new RedirectResponse($this->urlGenerator->generate('admin_news_show', ['id' => $news]));
    }
}
