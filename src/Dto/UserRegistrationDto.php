<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class UserRegistrationDto
{
    #[Assert\NotBlank(message: 'Email не может быть пустым')]
    #[Assert\Email(message: 'Некорректный email')]
    public string $email = '';

    #[Assert\NotBlank(message: 'Пароль не может быть пустым')]
    #[Assert\Length(
        min: 8,
        max: 20,
        minMessage: 'Длина пароля должна быть не менее 8 символов!',
        maxMessage: 'Длина пароля должна быть не более 20 символов!'
    )]
    public string $password = '';

    #[Assert\NotBlank(message: 'Подтвердите пароль!')]
    #[Assert\Length(
        min: 8,
        max: 20,
        minMessage: 'Длина пароля должна быть не менее 8 символов!',
        maxMessage: 'Длина пароля должна быть не более 20 символов!'
    )]
    #[Assert\EqualTo(propertyPath: 'password', message: 'Пароли не совпадают')]
    public string $passwordRepeat = '';
}
