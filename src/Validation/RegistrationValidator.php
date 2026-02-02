<?php

declare(strict_types=1);

namespace App\Validation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function strlen;
use const FILTER_VALIDATE_EMAIL;

final class RegistrationValidator
{
    public function validate(Request $request): ?Response
    {
        $name = (string) $request->request->get('username', '');
        $email = (string) $request->request->get('email', '');
        $password = (string) $request->request->get('password', '');

        if ($this->isEmpty($name) || $this->isEmpty($email) || $this->isEmpty($password)) {
            return new Response('Все поля должны быть заполнены!', 409);
        }

        if (!$this->isValidName($name)) {
            return new Response(
                'Имя должно содержать только латиницу, быть одним словом и иметь не менее 3 символов!',
                409
            );
        }

        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response('Некорректный Email!', 409);
        }

        if (!$this->isValidPassword($password)) {
            return new Response(
                'Пароль должен иметь от 8 до 30 символов и не иметь пробелов!',
                409
            );
        }

        return null; // Валидация пройдена
    }

    private function isEmpty(string $value): bool
    {
        return '' === $value;
    }

    private function isValidName(string $value): bool
    {
        return 1 === preg_match('!^[a-zA-Z]{3,}$!', $value);
    }

    private function isValidPassword(string $password): bool
    {
        if (strlen($password) < 8 || strlen($password) > 30) {
            return false;
        }

        return !str_contains($password, ' ')
            && !str_contains($password, "\t")
            && !str_contains($password, "\n")
            && !str_contains($password, "\r");
    }
}
