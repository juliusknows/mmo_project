<?php

declare(strict_types=1);

namespace App\Request;

use JsonException;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use const JSON_THROW_ON_ERROR;

final class JsonDecoderRequest
{
    /**
     *
     * Задача слушателя декодировать входящие json запросы.
     *
     * @noinspection PhpUnused
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $type = $request->getContentTypeFormat();

        if ('json' !== $type) {
            return;
        }
        $content = $request->getContent();

        try {
            $decodeContent = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
            $request->attributes->set('json', $decodeContent);
        } catch (JsonException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
