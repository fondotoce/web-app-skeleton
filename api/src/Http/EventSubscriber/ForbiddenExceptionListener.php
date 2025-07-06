<?php

namespace App\Http\EventSubscriber;

use App\Http\Exception\ForbiddenException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ForbiddenExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 2],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (!$exception instanceof ForbiddenException) {
            return;
        }

        if (!str_starts_with($request->attributes->get('_route'), 'app_')) {
            return;
        }

        $event->setResponse(new JsonResponse(
            [
                'name' => $exception->getName(),
                'message' => $exception->getMessage() ?? 'An error occurred.',
                'code' => Response::HTTP_FORBIDDEN,
                'status' => Response::HTTP_FORBIDDEN,
            ],
            Response::HTTP_FORBIDDEN,
            [
                'ContentType' => $request->headers->get('Accept', 'application/json'),
            ]
        ));
        $event->stopPropagation();
    }
}
