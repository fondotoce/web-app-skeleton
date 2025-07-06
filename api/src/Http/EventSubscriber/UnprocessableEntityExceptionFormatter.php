<?php

declare(strict_types=1);

namespace App\Http\EventSubscriber;

use App\Exception\Error;
use App\Exception\UnprocessableEntityExceptionInterface;
use App\Http\Exception\UnprocessableEntityException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UnprocessableEntityExceptionFormatter implements EventSubscriberInterface
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

        if (
            !$exception instanceof UnprocessableEntityException
        ) {
            return;
        }

        if (!str_starts_with($request->attributes->get('_route'), 'app_')) {
            return;
        }

        if ($exception->getPrevious() instanceof UnprocessableEntityExceptionInterface) {
            /** @var UnprocessableEntityExceptionInterface $serviceException */
            $serviceException = $exception->getPrevious();

            $errors = array_map(fn(Error $error) => [
                $error->field => $error->message
            ], $serviceException->getErrors());
        } else {
            // TODO log Warning "Get validation error without error descriptions."
            $errors = [];
        }

        $event->setResponse(new JsonResponse(
            [
                'name' => $exception->getName(),
                'message' => $exception->getMessage() ?: 'Data Validation Failed.',
                'errors' => $errors,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY,
            [
                'ContentType' => $request->headers->get('Accept', 'application/json'),
            ]
        ));
    }
}
