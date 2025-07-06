<?php

declare(strict_types=1);

namespace App\Http\EventSubscriber;

use App\Http\Exception\NotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class NotFoundExceptionFormatter implements EventSubscriberInterface
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

        if (!$exception instanceof NotFoundException) {
            return;
        }

        if (!str_starts_with($request->attributes->get('_route'), 'app_')) {
            return;
        }

        $event->setResponse(new JsonResponse(
            [
                'name' => 'Not found.',
                'message' => $exception->getMessage() ?? 'Object not found.',
                'code' => Response::HTTP_NOT_FOUND,
                'status' => Response::HTTP_NOT_FOUND,
            ],
            Response::HTTP_NOT_FOUND,
            [
                'ContentType' => $request->headers->get('Accept', 'application/json'),
            ]
        ));
    }
}
