<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace App\Subscriber;

use JsonException;
use Override;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use function is_array;
use const JSON_THROW_ON_ERROR;

final class RequestJsonDecoderSubscriber implements EventSubscriberInterface
{
    /**
     * Задача слушателя декодировать все входящие json запросы и добавить к запросу раскодированное тело.
     */
    #[Override]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', 100],
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $content = $request->getContent();

        if ('json' !== $request->getContentTypeFormat() || '' === $content) {
            return;
        }

        try {
            $decodeContent = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
            $result = is_array($decodeContent) ? $decodeContent : [];
            $request->request->replace($result);
        } catch (JsonException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
