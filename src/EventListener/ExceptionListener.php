<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\EventListener;

use BitBag\SyliusIngPlugin\Exception\IngClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
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

        if ($exception instanceof IngClientExceptionInterface) {
            $this->handleHttpException($exception, $event);
        }
    }

    /**
     * @param IngClientExceptionInterface|\Throwable $exception
     */
    private function handleHttpException($exception, ExceptionEvent $event): void
    {
        $response = $event->getResponse() ?? new Response();

        $response->setStatusCode($exception->getStatusCode());

        $event->setResponse($response);
    }
}
