<?php

declare(strict_types=1);

namespace App\Exception;

interface ErrorDetailsContainerInterface
{
    /**
     * @return array<string, array<string>>
     */
    public function getErrorDetails(): array;
}
