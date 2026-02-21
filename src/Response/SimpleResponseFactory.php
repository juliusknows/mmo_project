<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace App\Response;

use App\Exception\ErrorDetailsContainerInterface;
use JsonSerializable;
use Override;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

final readonly class SimpleResponseFactory implements AbstractResponseFactory
{
    public function __construct(private string $env)
    {
    }

    /**
     * @param array<string, string> $headers HTTP-заголовки для ответа
     */
    #[Override]
    public function createByException(Throwable $exception, ?int $httpCode = null, array $headers = []): JsonResponse
    {
        $headers += $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [];
        $httpCode ??= $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        $details = $exception instanceof ErrorDetailsContainerInterface
            ? $exception->getErrorDetails()
            : ('prod' === $this->env
                ? []
                : [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]);

        return $this->create(
            data: new ErrorResponse($exception->getMessage(), $details),
            httpCode: $httpCode,
            headers: $headers
        );
    }

    /**
     * @param array<string>|JsonSerializable $data
     * @param array<string,string>           $headers
     */
    public function create(
        array|JsonSerializable $data,
        int $httpCode = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        return new JsonResponse($data, $httpCode, $headers);
    }
}
