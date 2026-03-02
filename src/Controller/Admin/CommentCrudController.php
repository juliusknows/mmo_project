<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\News;
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

    public function create(News $news, CommentPublicationRequest $request): RedirectResponse
    {
        $this->commentManager->create($news, $request);

        return new RedirectResponse($this->urlGenerator->generate('admin_news_show', ['id' => $news->getId()]));
    }

    public function delete(Comment $comment): RedirectResponse
    {
        $news = $this->commentManager->remove($comment);

        return new RedirectResponse($this->urlGenerator->generate('admin_news_show', ['id' => $news->getId()]));
    }

    public function createEditForm(Comment $comment): Response
    {
        return new Response($this->twig->render('/admin/news/editFormComment.html.twig', [
            'comment' => $comment,
        ]));
    }

    public function edit(Comment $comment, CommentPublicationRequest $request): RedirectResponse
    {
        $news = $this->commentManager->edit($comment, $request);

        return new RedirectResponse($this->urlGenerator->generate('admin_news_show', ['id' => $news->getId()]));
    }
}
