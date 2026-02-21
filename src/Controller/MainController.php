<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\UserRegistrationRequest;
use App\Validator\EntityRegistrator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class MainController extends AbstractController
{
    public function __construct(private readonly EntityRegistrator $entityRegistrator)
    {
    }

    public function indexAction(): Response
    {
        return $this->render('test.html.twig');
    }

    public function showRegistrationFormAction(): Response
    {
        return $this->render('registration/registration.html.twig');
    }

    public function registrationAction(UserRegistrationRequest $request): JsonResponse
    {
        $this->entityRegistrator->registerUser($request->getEmail(), $request->getPassword());

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Успешная регистрация!',
        ]);
    }
}
