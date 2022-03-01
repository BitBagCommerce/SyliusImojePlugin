<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop;

use BitBag\SyliusIngPlugin\Bus\Command\FinalizeOrder;
use BitBag\SyliusIngPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPlugin\Bus\Query\GetResponseData;
use BitBag\SyliusIngPlugin\Factory\Bus\PaymentFinalizationCommandFactoryInterface;
use BitBag\SyliusIngPlugin\Generator\Url\Status\AggregateStatusBasedUrlGeneratorInterface;
use BitBag\SyliusIngPlugin\Model\ReadyTransaction\ReadyTransactionModelInterface;
use BitBag\SyliusIngPlugin\Resolver\Status\StatusResolverInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RedirectController
{
    private DispatcherInterface $dispatcher;

    private PaymentFinalizationCommandFactoryInterface $commandFactory;

    private AggregateStatusBasedUrlGeneratorInterface $aggregateStatusBasedUrlGenerator;

    private StatusResolverInterface $statusResolver;

    public function __construct(
        DispatcherInterface $dispatcher,
        PaymentFinalizationCommandFactoryInterface $commandFactory,
        AggregateStatusBasedUrlGeneratorInterface $aggregateStatusBasedUrlGenerator,
        StatusResolverInterface $statusResolver
    ) {
        $this->dispatcher = $dispatcher;
        $this->commandFactory = $commandFactory;
        $this->aggregateStatusBasedUrlGenerator = $aggregateStatusBasedUrlGenerator;
        $this->statusResolver = $statusResolver;
    }

    public function __invoke(
        Request $request,
        string $status,
        int $paymentId
    ): Response {
        /** @var ReadyTransactionModelInterface $readyTransaction */
        $readyTransaction = $this->dispatcher->dispatch(new GetResponseData($paymentId));

        $payment = $readyTransaction->getIngTransaction()->getPayment();

        $order = $readyTransaction->getOrder();

        $this->dispatcher->dispatch(new FinalizeOrder($order));

        $paymentStatus = $this->statusResolver->resolve($readyTransaction->getStatus());

        $this->dispatcher->dispatch(
            $this->commandFactory->createNew($paymentStatus, $payment)
        );

        $url = $this->aggregateStatusBasedUrlGenerator->generate($order, $request, $paymentStatus);

        return new RedirectResponse($url);
    }
}
