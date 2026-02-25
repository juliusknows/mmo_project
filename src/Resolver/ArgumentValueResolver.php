<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace App\Resolver;

use App\Exception\RequestValidationException;
use App\Request\RequestInterface;
use App\Validator\ThrowableValidator;
use Override;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * Задача класса передать в контроллер заполненную и валидную форму имплементирующие указанный интерфейс.
 * IDE не видит неочевидный вызов резолвера из-за динамической регистрации в Symfony.
 *
 * @noinspection PhpUnused
 */
final readonly class ArgumentValueResolver implements ValueResolverInterface
{
    public function __construct(private ThrowableValidator $troubleValidator)
    {
    }

    /**
     * @return iterable<RequestInterface>
     */
    #[Override]
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $className = $argument->getType();

        if (!is_a($className, RequestInterface::class, true)) {
            return [];
        }

        try {
            $customRequest = new $className($request);
            $this->troubleValidator->validate($customRequest, null, 'registration');
        } catch (ValidationFailedException $e) {
            $violations = $e->getViolations();

            throw new RequestValidationException($violations);
        }

        return [$customRequest];
    }
}
