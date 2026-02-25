<?php

declare(strict_types=1);

namespace App\Request;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class UserInputEmailRequest implements RequestInterface
{
    #[Assert\Valid]
    private User $user;

    public function __construct(Request $request)
    {
        $inputEmail = $request->request->get('email');
        $email = strtolower(trim((string) $inputEmail));
        $this->user = new User();
        $this->user->setEmail($email);
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
