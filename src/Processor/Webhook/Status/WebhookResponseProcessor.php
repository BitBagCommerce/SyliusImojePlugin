<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Processor\Webhook\Status;

use BitBag\SyliusIngPlugin\Bus\Command\FinalizeOrder;
use BitBag\SyliusIngPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPlugin\Factory\Bus\PaymentFinalizationCommandFactoryInterface;
use BitBag\SyliusIngPlugin\Model\Status\StatusResponseModelInterface;
use BitBag\SyliusIngPlugin\Resolver\Status\StatusResolverInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\OrderCheckoutTransitions;

final class WebhookResponseProcessor implements WebhookResponseProcessorInterface
{
    private PaymentFinalizationCommandFactoryInterface $commandFactory;

    private DispatcherInterface $dispatcher;

    private LoggerInterface $logger;

    private StatusResolverInterface $statusResolver;

    public function __construct(
        PaymentFinalizationCommandFactoryInterface $commandFactory,
        DispatcherInterface $dispatcher,
        LoggerInterface $logger,
        StatusResolverInterface $statusResolver
    ) {
        $this->commandFactory = $commandFactory;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->statusResolver = $statusResolver;
    }

    public function process(StatusResponseModelInterface $response, PaymentInterface $payment): void
    {
        $statusCode = $response->getStatus();
        $status = $this->statusResolver->resolve($statusCode);

        if ('success' !== $status) {
            $this->logOnError($response->getTransactionId(), $status);
        }

        $this->logger->debug(
            \sprintf('Dispatching payment finalization command for status: %s', $response->getStatus())
        );

        $this->finalizeOrderIfNotAlreadyComplete($payment);

        $command = $this->commandFactory->createNew($status, $payment);

        $this->dispatcher->dispatch($command);
    }

    private function logOnError(string $transactionId, string $statusCode): void
    {
        $this->logger->error(\sprintf(
            'Got failure for transaction [%s]. Payment status code: [%s]',
            $transactionId,
            $statusCode
        ));
    }

    private function finalizeOrderIfNotAlreadyComplete(PaymentInterface $payment): void
    {
        $order = $payment->getOrder();

        if (OrderCheckoutTransitions::TRANSITION_COMPLETE !== $order->getState()) {
            $this->dispatcher->dispatch(new FinalizeOrder($order));
        }
    }
}
