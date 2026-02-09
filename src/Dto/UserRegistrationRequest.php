<?php

declare(strict_types=1);

namespace App\Dto;

final class UserRegistrationRequest
{
    public string $email;

    public string $password;

    public string $passwordRepeat;

    public function __construct(string $email, string $password, string $passwordRepeat)
    {
        $this->email = $email;
        $this->password = $password;
        $this->passwordRepeat = $passwordRepeat;
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, false);

        return new self(
            (string) $data->email,
            (string) $data->password,
            (string) $data->passwordRepeat
        );
    }
}
