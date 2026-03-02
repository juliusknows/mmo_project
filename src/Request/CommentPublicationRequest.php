<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class CommentPublicationRequest implements RequestInterface
{
    #[Assert\NotBlank(message: 'Поле автора не заполнено', groups: ['registration'])]
    private string $author;

    #[Assert\NotBlank(message: 'Поле комментария не заполнено', groups: ['registration'])]
    private string $text;

    public function __construct(Request $request)
    {
        $data = $request->request->all();

        $this->author = trim((string) $data['author']);
        $this->text = trim((string) $data['text']);
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
