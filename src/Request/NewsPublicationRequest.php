<?php

declare(strict_types=1);

namespace App\Request;

use App\Entity\News;
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

    #[Assert\Valid(groups: ['registration'])]
    private News $news;

    public function __construct(Request $request)
    {
        $data = $request->request->all();

        $this->news = new News();
        $this->title = trim((string) $data['title']);
        $this->summary = trim((string) $data['summary']);
        $this->content = trim((string) $data['content']);
        $this->news->setTitle($this->title);
        $this->news->setSummary($this->summary);
        $this->news->setContent($this->content);
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

    public function getNews(): News
    {
        return $this->news;
    }
}
