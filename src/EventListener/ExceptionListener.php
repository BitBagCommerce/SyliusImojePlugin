<?php

declare(strict_types=1);

use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ExceptionListener
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof IngBadRequestException) {
            $this->handleLoggableException($exception);
        }
    }

    private function handleLoggableException(IngBadRequestException $exception): void
    {
        $this->logger->error($exception->getMessage());
    }
}
