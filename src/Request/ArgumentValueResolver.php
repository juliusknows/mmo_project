<?php

declare(strict_types=1);

namespace App\Request;

use App\Service\ThrowableValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * Задача класса передать в контроллер заполненную и валидную форму имплементирующие указанный интерфейс
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
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $className = $argument->getType();

        if (!is_a($className, RequestInterface::class, true)) {
            return [];
        }

        try {
            $dto = new $className($request);
            $this->troubleValidator->validate($dto);
        } catch (ValidationFailedException $exception) {
            throw new BadRequestHttpException('Ошибка при попытке создать DTO: ' . $exception->getMessage());
        }

        return [$dto];
    }
}
