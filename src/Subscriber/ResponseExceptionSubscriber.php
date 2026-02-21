<?php

/** @noinspection ALL */

declare(strict_types=1);

namespace App\Subscriber;

use App\Response\SimpleResponseFactory;
use Override;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ResponseExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private string $environment)
    {
    }

    /**
     * Задача подписчика поймать ошибку и вернуть единообразный ответ
     */
    #[Override]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', -128],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $factory = new SimpleResponseFactory($this->environment);
        $response = $factory->createByException($exception);
        $event->setResponse($response);
    }
}
