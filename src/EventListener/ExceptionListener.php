<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\EventListener;

use BitBag\SyliusIngPayPlugin\Exception\IngPayClientExceptionInterface;
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

        if ($exception instanceof IngPayClientExceptionInterface) {
            $this->handleLoggableException($exception);
        }
    }

    private function handleLoggableException(IngPayClientExceptionInterface $exception): void
    {
        $this->logger->error($exception->getMessage());
    }
}
