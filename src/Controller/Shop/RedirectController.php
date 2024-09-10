<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Controller\Shop;

use BitBag\SyliusImojePlugin\Bus\Command\FinalizeOrder;
use BitBag\SyliusImojePlugin\Bus\DispatcherInterface;
use BitBag\SyliusImojePlugin\Bus\Query\GetResponseData;
use BitBag\SyliusImojePlugin\Factory\Bus\PaymentFinalizationCommandFactoryInterface;
use BitBag\SyliusImojePlugin\Generator\Url\Status\AggregateStatusBasedUrlGeneratorInterface;
use BitBag\SyliusImojePlugin\Model\ReadyTransaction\ReadyTransactionModelInterface;
use BitBag\SyliusImojePlugin\Resolver\Status\StatusResolverInterface;
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
        StatusResolverInterface $statusResolver,
    ) {
        $this->dispatcher = $dispatcher;
        $this->commandFactory = $commandFactory;
        $this->aggregateStatusBasedUrlGenerator = $aggregateStatusBasedUrlGenerator;
        $this->statusResolver = $statusResolver;
    }

    public function __invoke(
        Request $request,
        string $status,
        int $paymentId,
    ): Response {
        /** @var ReadyTransactionModelInterface $readyTransaction */
        $readyTransaction = $this->dispatcher->dispatch(new GetResponseData($paymentId));

        $payment = $readyTransaction->getimojeTransaction()->getPayment();

        $order = $readyTransaction->getOrder();

        $this->dispatcher->dispatch(new FinalizeOrder($order));

        $paymentStatus = $this->statusResolver->resolve($readyTransaction->getStatus());

        $this->dispatcher->dispatch(
            $this->commandFactory->createNew($paymentStatus, $payment),
        );

        $url = $this->aggregateStatusBasedUrlGenerator->generate($order, $request, $paymentStatus);

        return new RedirectResponse($url);
    }
}
