<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class NewsPublicationRequest implements RequestInterface
{
    #[Assert\NotBlank(
        message: 'Поле title не может быть пустым!',
        groups: ['registration']
    )]
    private string $title;

    #[Assert\NotBlank(
        message: 'Поле summary не может быть пустым!',
        groups: ['registration']
    )]
    private string $summary;

    #[Assert\NotBlank(
        message: 'Поле content не может быть пустым!',
        groups: ['registration']
    )]
    private string $content;

    public function __construct(Request $request)
    {
        $data = $request->request->all();

        $this->title = $data['title'] ?? '';
        $this->summary = $data['summary'] ?? '';
        $this->content = $data['content'] ?? '';
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
