<?php

declare(strict_types=1);

namespace App\Request;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class CommentPublicationRequest implements RequestInterface
{
    #[Assert\Valid(groups: ['registration'])]
    private Comment $comment;

    #[Assert\NotBlank(message: 'Поле автора не заполнено', groups: ['registration'])]
    private string $author;

    #[Assert\NotBlank(message: 'Поле комментария не заполнено', groups: ['registration'])]
    private string $text;

    private int $newsId;

    public function __construct(Request $request)
    {
        $data = $request->request->all();

        $this->comment = new Comment();
        $this->author = trim((string) $data['author']);
        $this->text = trim((string) $data['text']);
        $this->comment->setAuthor($this->author);
        $this->comment->setText($this->text);
        $this->newsId = (int) $data['news_id'];
    }

    public function getComment(): Comment
    {
        return $this->comment;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getNewsId(): int
    {
        return $this->newsId;
    }
}
