<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop;

use BitBag\SyliusIngPlugin\Bus\Command\SaveTransaction;
use BitBag\SyliusIngPlugin\Bus\Command\TakeOverPayment;
use BitBag\SyliusIngPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPlugin\Bus\Query\GetTransactionData;
use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Exception\IngNotConfiguredException;
use BitBag\SyliusIngPlugin\Factory\Payment\PaymentDataModelFactoryInterface;
use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\Payment\OrderPaymentResolverInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class InitializePaymentController
{
    private OrderResolverInterface $orderResolver;

    private OrderPaymentResolverInterface $paymentResolver;

    private DispatcherInterface $dispatcher;

    private PaymentDataModelFactoryInterface $paymentDataModelFactory;

    public function __construct(
        OrderResolverInterface $orderResolver,
        OrderPaymentResolverInterface $paymentResolver,
        DispatcherInterface $dispatcher,
        PaymentDataModelFactoryInterface $paymentDataModelFactory
    ) {
        $this->orderResolver = $orderResolver;
        $this->paymentResolver = $paymentResolver;
        $this->dispatcher = $dispatcher;
        $this->paymentDataModelFactory = $paymentDataModelFactory;
    }

    public function __invoke(Request $request): Response
    {
        $code = $request->query->get('code');
        $order = $this->orderResolver->resolve();

        try {
            $payment = $this->paymentResolver->resolve($order);
        } catch (\InvalidArgumentException $e) {
            throw new IngNotConfiguredException('Payment method not found');
        }

        if ($code !== null) {
            $this->dispatcher->dispatch(new TakeOverPayment($payment, $code));
        }
        $transactionPaymentData = $this->paymentDataModelFactory->create($payment);

        /** @var IngTransactionInterface $transactionData */
        $transactionData = $this->dispatcher->dispatch(
            new GetTransactionData(
                $order,
                $payment->getMethod()->getCode(),
                $transactionPaymentData->getPaymentMethod(),
                $transactionPaymentData->getPaymentMethodCode()
            )
        );

        $this->dispatcher->dispatch(new SaveTransaction($transactionData));

        return new RedirectResponse($transactionData->getPaymentUrl());
    }
}
