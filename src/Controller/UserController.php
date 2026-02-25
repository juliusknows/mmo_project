<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final readonly class UserController
{
    public function __construct(
        private Environment $twig
    ) {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function showTestAction(): Response
    {
        return new Response($this->twig->render('test.html.twig'));
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function showRegistrationAction(): Response
    {
        return new Response($this->twig->render('registration/registration.html.twig', [
            'title' => 'Регистрация',
        ]));
    }
}
