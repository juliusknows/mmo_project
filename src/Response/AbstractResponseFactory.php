<?php

declare(strict_types=1);

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

interface AbstractResponseFactory
{
    /**
     * @param array<string, string> $headers HTTP-заголовки для ответа
     */
    public function createByException(Throwable $exception, ?int $httpCode = null, array $headers = []): JsonResponse;
}
