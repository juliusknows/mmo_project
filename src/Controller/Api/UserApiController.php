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
        $result = ['redirect' => '/'];

        return new JsonResponse($result, Response::HTTP_OK);
    }

    /**
     * Валидация пройдена на более высоком уровне, если код дошел сюда то все хорошо!
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
