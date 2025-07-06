<?php

namespace App\Http;

use Psr\Log\LoggerInterface;

class ErrorHandler
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function handle(\Throwable $e): void
    {
        $this->logger->warning($e->getMessage(), ['exception' => $e]);
    }
}
