<?php

/** @noinspection PhpPropertyOnlyWrittenInspection */

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UserRegistrationRequest implements RequestInterface
{
    #[Assert\NotBlank(message: 'Поле email не может быть пустым!')]
    #[Assert\Email(message: 'Некорректный email!')]
    private string $email;

    #[Assert\NotBlank(message: 'Поле password не может быть пустым!')]
    #[Assert\PasswordStrength(minScore: 1, message: 'Слишком простой пароль!')]
    private string $password;

    #[Assert\NotBlank(message: 'Поле passwordRepeat не может быть пустым!')]
    #[Assert\IdenticalTo(propertyPath: 'password', message: 'Пароли не совпадают!')]
    /**
     * Повтор пароля для проверки совпадения с основным паролем.
     * Используется исключительно механизмом валидации Symfony (Assert‑аннотации).
     * Не предназначено для чтения в бизнес‑логике.
     *
     * @phpstan-ignore-next-line
     */
    private string $passwordRepeat;

    /**
     * @var array<string>
     */
    private array $accepts;

    public function __construct(Request $request)
    {
        $acceptableTypes = $request->getAcceptableContentTypes();

        $data = $request->request->all();

        $this->email = strtolower(trim((string) ($data['email'] ?? '')));
        $this->password = trim((string) ($data['password'] ?? ''));
        $this->passwordRepeat = trim((string) ($data['passwordRepeat'] ?? ''));
        $this->accepts = $acceptableTypes;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return array<string> Список MIME‑типов (например, 'application/json', 'text/html')
     */
    public function getAccept(): array
    {
        return $this->accepts;
    }
}
