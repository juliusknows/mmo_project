<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use App\Request\UserRegistrationRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

final class MainController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function indexAction(): Response
    {
        return $this->render('test.html.twig');
    }

    public function registrationAction(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('registration');
        }

        return $this->render('registration/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function apiRegistrationAction(UserRegistrationRequest $requestApi): JsonResponse
    {
        $user = new User();
        $user->setEmail($requestApi->getEmail());
        $user->setPassword($requestApi->getPassword());

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (Throwable $exception) {
            return new JsonResponse([
                'success' => true,
                'error' => $exception->getMessage(),
                'message' => 'Такой пользователь уже существует!',
            ], Response::HTTP_OK);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Пользователь зарегистрирован',
        ], Response::HTTP_OK);
    }

    public function checkEmail(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $email = $data->email ?? '';

        $user = new User();
        $user->setEmail($email);
        $violations = $this->validator->validate($user, null, ['email']);

        if ($violations->count() > 0) {
            return new JsonResponse([
                'success' => false,
                'messageMail' => $violations->get(0)->getMessage(),
                'error' => 'Ошибка валидации email',
            ]);
        }

        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $email]);
        $result = null === $user;

        return new JsonResponse([
            'success' => true,
            'messageMail' => $result ? 'Этот email свободен!' : 'Этот email уже занят!',
            'data' => $user,
        ]);
    }

    public function checkPassword(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $password = $data->password ?? '';
        $passwordRepeat = $data->passwordRepeat ?? '';

        $user = new User();
        $user->setPassword($password);

        $violations = $this->validator->validate($user, null, ['password']);

        if ($violations->count() > 0) {
            return new JsonResponse([
                'success' => false,
                'messagePass' => $violations->get(0)->getMessage(),
                'error' => 'Ошибка валидации пароля',
                'data' => [
                    'password' => false,
                ],
            ]);
        }

        if ($password !== $passwordRepeat) {
            return new JsonResponse([
                'success' => true,
                'messagePass' => 'Хороший пароль!',
                'messageRepeat' => 'Пароли не совпадают!',
                'error' => 'Подтверждение не валидно',
                'data' => [
                    'password' => true,
                    'passwordRepeat' => false,
                ],
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'messagePass' => 'Хороший пароль!',
            'messageRepeat' => 'Совпадают!',
            'data' => [
                'password' => true,
                'passwordRepeat' => true,
            ],
        ]);
    }
}
