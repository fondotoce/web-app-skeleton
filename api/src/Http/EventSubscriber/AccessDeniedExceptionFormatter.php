<?php

declare(strict_types=1);

namespace App\Http\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class AccessDeniedExceptionFormatter implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (!$exception instanceof AccessDeniedHttpException) {
            return;
        }

        if (!str_starts_with($request->attributes->get('_route'), 'app_')) {
            return;
        }

        $event->setResponse(new JsonResponse(
            [
                'name' => 'Forbidden',
                'message' => 'You don`t have permission to perform this action.',
                'code' => Response::HTTP_FORBIDDEN,
                'status' => Response::HTTP_FORBIDDEN,
            ],
            Response::HTTP_FORBIDDEN,
            [
                'ContentType' => $request->headers->get('Accept', 'application/json'),
            ]
        ));
    }
}
