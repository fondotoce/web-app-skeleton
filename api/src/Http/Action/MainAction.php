<?php
declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Exception\ServerErrorException as ServerErrorHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/', name: 'app_main', methods: ['GET'])]
final class MainAction
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            return new JsonResponse([
                'app' => 'Web API skeleton',
                'version' => '1.0.0',
                'description' => 'Welcome to the Web API skeleton.',
            ]);
        } catch (\Throwable $t) {
            throw new ServerErrorHttpException('An error occurred.', previous: $t);
        }
    }
}
