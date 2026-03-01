<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final readonly class UserController
{
    public function __construct(
        private Environment $twig
    ) {
    }

    public function showTestAction(): Response
    {
        return new Response($this->twig->render('test.html.twig'));
    }

    public function showRegistrationAction(): Response
    {
        return new Response($this->twig->render('registration/registration.html.twig', [
            'title' => 'Регистрация',
        ]));
    }
}
