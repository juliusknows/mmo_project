<?php

/** @noinspection PhpUnused */
/** @noinspection PhpUnusedPrivateMethodInspection */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['email'], message: 'Пользователь с таким Email уже зарегистрирован!')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Assert\NotBlank(message: 'Email не может быть пустым!')]
    #[Assert\Email(message: 'Некорректный email!')]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[Assert\NotBlank(message: 'Пароль не может быть пустым!')]
    #[Assert\PasswordStrength(
        minScore: Assert\PasswordStrength::STRENGTH_WEAK,
        message: 'Слишком простой пароль! Придумайте посложнее!'
    )]
    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[Assert\NotBlank(message: 'Подтвердите пароль!')]
    #[Assert\IdenticalTo(propertyPath: 'password', message: 'Введённые пароли не совпадают. Проверьте, что оба поля содержат одинаковый пароль!')]
    private string $passwordRepeat;

    /**
     * @phpstan-ignore-next-line
     */
    private function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPasswordRepeat(): string
    {
        return $this->passwordRepeat;
    }

    public function setPasswordRepeat(string $passwordRepeat): static
    {
        $this->passwordRepeat = $passwordRepeat;

        return $this;
    }
}
