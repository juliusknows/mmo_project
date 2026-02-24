<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\UserRegistrationRequest;
use App\Validator\EntityRegistrator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use function in_array;

final readonly class MainController
{
    public function __construct(
        private EntityRegistrator $entityRegistrator,
        private Environment $twig
    ) {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function indexAction(): Response
    {
        return new Response($this->twig->render('test.html.twig'));
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function showRegistrationFormAction(): Response
    {
        return new Response($this->twig->render('registration/registration.html.twig', [
            'title' => 'Регистрация',
        ]));
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function registrationAction(UserRegistrationRequest $request): Response
    {
        $this->entityRegistrator->registerUser($request->getEmail(), $request->getPassword());

        if (in_array('application/json', $request->getAccept(), true)) {
            return new JsonResponse([
                'status' => 'success',
                'message' => 'Успешная регистрация!',
            ]);
        }

        return new Response($this->twig->render('registration/registration.html.twig', [
            'title' => 'Успешная регистрация!',
        ]));
    }
}
