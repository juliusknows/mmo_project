<?php

/** @noinspection PhpPropertyOnlyWrittenInspection */

declare(strict_types=1);

namespace App\Request;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UserRegistrationRequest implements RequestInterface
{
    #[Assert\NotBlank(
        message: 'Поле email не может быть пустым!',
        groups: ['registration']
    )]
    #[Assert\Email(
        message: 'Некорректный email!',
        groups: ['registration']
    )]
    private string $email;

    #[Assert\NotBlank(
        message: 'Поле password не может быть пустым!',
        groups: ['registration']
    )]
    #[Assert\PasswordStrength(
        minScore: 1,
        groups: ['registration'],
        message: 'Слишком простой пароль!'
    )]
    private string $password;

    #[Assert\NotBlank(
        message: 'Поле passwordRepeat не может быть пустым!',
        groups: ['registration']
    )]
    #[Assert\IdenticalTo(
        propertyPath: 'password',
        message: 'Пароли не совпадают!',
        groups: ['registration']
    )]
    private string $passwordRepeat;

    #[Assert\Valid(groups: ['registration'])]
    private User $user;

    public function __construct(Request $request)
    {
        $data = $request->request->all();

        $this->user = new User();
        $this->email = strtolower(trim((string) ($data['email'] ?? '')));
        $this->password = trim((string) ($data['password'] ?? ''));
        $this->passwordRepeat = trim((string) ($data['passwordRepeat'] ?? ''));
        $this->user->setEmail($this->email);
        $this->user->setPassword($this->password);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordRepeat(): string
    {
        return $this->passwordRepeat;
    }
}
