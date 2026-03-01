<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Manager\UserManager;
use App\Request\UserInputEmailRequest;
use App\Request\UserRegistrationRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class UserApiController
{
    public function __construct(private UserManager $userManager)
    {
    }

    public function registrationAction(UserRegistrationRequest $request): JsonResponse
    {
        $this->userManager->register($request->getUser());
        $result = ['redirect' => '/admin'];

        return new JsonResponse($result, Response::HTTP_OK);
    }

    /**
     * Проверка вводимого email.
     *
     * @noinspection PhpUnusedParameterInspection
     */
    public function checkEmailAction(UserInputEmailRequest $request): JsonResponse
    {
        return new JsonResponse([
            'status' => 'success',
        ]);
    }
}
