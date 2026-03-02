<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\News;
use App\Repository\CommentRepository;
use App\Request\CommentPublicationRequest;

final readonly class CommentManager
{
    public function __construct(
        private CommentRepository $commentRepository,
    ) {
    }

    public function create(News $news, CommentPublicationRequest $request): void
    {
        $comment = new Comment();
        $comment->setAuthor($request->getAuthor());
        $comment->setText($request->getText());
        $comment->setNews($news);

        $this->commentRepository->createComment($comment);
    }

    public function remove(Comment $comment): News
    {
        $this->commentRepository->removeComment($comment);

        return $comment->getNews();
    }

    public function getTotal(): int
    {
        return $this->commentRepository->count();
    }

    public function edit(Comment $comment, CommentPublicationRequest $request): News
    {
        $comment->setAuthor($request->getAuthor());
        $comment->setText($request->getText());

        $this->commentRepository->edit();

        return $comment->getNews();
    }
}
