<?php

declare(strict_types=1);

namespace App\Http\EventSubscriber;

use App\Http\ErrorHandler;
use App\Http\Exception\ServerErrorException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ServerErrorExceptionFormatter implements EventSubscriberInterface
{
    public function __construct(private readonly ErrorHandler $errors)
    {
    }

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

        if (!$exception instanceof ServerErrorException) {
            return;
        }

        if (!str_starts_with($request->attributes->get('_route'), 'app_')) {
            return;
        }

        $this->errors->handle($exception);

        $event->setResponse(new JsonResponse(
            [
                'name' => $exception->getName(),
                'message' => $exception->getMessage() ?? 'An error occurred.',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR,
            [
                'ContentType' => $request->headers->get('Accept', 'application/json'),
            ]
        ));
    }
}
