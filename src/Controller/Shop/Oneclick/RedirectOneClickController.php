<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Controller\Shop\Oneclick;

use BitBag\SyliusImojePlugin\Bus\Command\FinalizeOrder;
use BitBag\SyliusImojePlugin\Bus\DispatcherInterface;
use BitBag\SyliusImojePlugin\Factory\Bus\PaymentFinalizationCommandFactoryInterface;
use BitBag\SyliusImojePlugin\Generator\Url\Status\AggregateStatusBasedUrlGeneratorInterface;
use BitBag\SyliusImojePlugin\Resolver\Status\StatusResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Repository\PaymentRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RedirectOneClickController
{
    private DispatcherInterface $dispatcher;

    private PaymentFinalizationCommandFactoryInterface $commandFactory;

    private AggregateStatusBasedUrlGeneratorInterface $aggregateStatusBasedUrlGenerator;

    private PaymentRepositoryInterface $paymentRepository;

    private StatusResolverInterface $statusResolver;

    public function __construct(
        DispatcherInterface $dispatcher,
        PaymentFinalizationCommandFactoryInterface $commandFactory,
        AggregateStatusBasedUrlGeneratorInterface $aggregateStatusBasedUrlGenerator,
        PaymentRepositoryInterface $paymentRepository,
        StatusResolverInterface $statusResolver,
    ) {
        $this->dispatcher = $dispatcher;
        $this->commandFactory = $commandFactory;
        $this->aggregateStatusBasedUrlGenerator = $aggregateStatusBasedUrlGenerator;
        $this->paymentRepository = $paymentRepository;
        $this->statusResolver = $statusResolver;
    }

    public function __invoke(
        Request $request,
        string $status,
        int $paymentId,
    ): Response {
        /** @var PaymentInterface|null $payment */
        $payment = $this->paymentRepository->find($paymentId);

        /** @var OrderInterface $order */
        $order = $payment->getOrder();
        $resolvedStatus = $this->statusResolver->resolve($status);
        $this->dispatcher->dispatch(new FinalizeOrder($order));
        $this->dispatcher->dispatch(
            $this->commandFactory->createNew($resolvedStatus, $payment),
        );

        $url = $this->aggregateStatusBasedUrlGenerator->generate($order, $request, $resolvedStatus);

        return new RedirectResponse($url);
    }
}
