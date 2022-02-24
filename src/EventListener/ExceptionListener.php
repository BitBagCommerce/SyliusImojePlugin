<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\EventListener;

use BitBag\SyliusIngPlugin\Exception\IngClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ExceptionListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof IngClientExceptionInterface) {
            $this->handleLoggableException($exception);
        }
    }

    private function handleLoggableException(IngClientExceptionInterface $exception): void
    {
        $this->logger->error($exception->getMessage());
    }
}
