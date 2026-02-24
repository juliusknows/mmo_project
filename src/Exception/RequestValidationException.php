<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace App\Exception;

use Override;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class RequestValidationException extends BadRequestHttpException implements ErrorDetailsContainerInterface
{
    private ConstraintViolationListInterface $violations;

    public function __construct(ConstraintViolationListInterface $violations)
    {
        parent::__construct('Ошибка валидации!');
        $this->violations = $violations;
    }

    #[Override]
    public function getErrorDetails(): array
    {
        $details = [];

        foreach ($this->violations as $violation) {
            $details[$violation->getPropertyPath()][] = (string) $violation->getMessage();
        }

        return $details;
    }
}
