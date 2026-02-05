<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

final class MainController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction(): Response
    {
        return new Response('Куку');
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

    public function checkEmail(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';

        $validator = Validation::createValidator();
        $violations = $validator->validate($email, [
            new Assert\Email(),
            new Assert\NotBlank(),
        ]);

        if ($violations->count() > 0) {
            return new JsonResponse([
                'error' => 'Некорректный email',
                'massage' => 'Валидация не пройдена',
            ]);
        }

        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        return new JsonResponse([
            'exists' => null !== $user,
            'message' => null !== $user ? 'Этот email уже занят' : 'Email доступен',
        ]);
    }
}
