<?php

declare(strict_types=1);

namespace App\Http\EventSubscriber;

use App\Http\Exception\BadRequestException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class BadRequestExceptionFormatter implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (!$exception instanceof BadRequestException) {
            return;
        }

        if (!str_starts_with($request->attributes->get('_route'), 'app_')) {
            return;
        }

        $event->setResponse(new JsonResponse(
            [
                'name' => $exception->getName(),
                'message' => $exception->getMessage() ?? 'An error occurred.',
                'code' => Response::HTTP_BAD_REQUEST,
                'status' => Response::HTTP_BAD_REQUEST,
            ],
            Response::HTTP_BAD_REQUEST,
            [
                'ContentType' => $request->headers->get('Accept', 'application/json'),
            ]
        ));
    }
}
