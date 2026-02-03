<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserRegistrationDto;
use App\Form\UserRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class MainController extends AbstractController
{
    public function index(): Response
    {
        return new Response('Тут пока пусто');
    }

    public function registration(Request $request): Response
    {
        $dto = new UserRegistrationDto();
        $form = $this->createForm(UserRegistrationType::class, $dto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Регистрация успешна!');

            return $this->redirectToRoute('registration');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
