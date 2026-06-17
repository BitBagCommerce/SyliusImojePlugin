<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Controller\Shop;

use BitBag\SyliusIngPayPlugin\Bus\Command\FinalizeOrder;
use BitBag\SyliusIngPayPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPayPlugin\Bus\Query\GetResponseData;
use BitBag\SyliusIngPayPlugin\Factory\Bus\PaymentFinalizationCommandFactoryInterface;
use BitBag\SyliusIngPayPlugin\Generator\Url\Status\AggregateStatusBasedUrlGeneratorInterface;
use BitBag\SyliusIngPayPlugin\Model\ReadyTransaction\ReadyTransactionModelInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Status\StatusResolverInterface;
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

        $payment = $readyTransaction->getingPayTransaction()->getPayment();

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
