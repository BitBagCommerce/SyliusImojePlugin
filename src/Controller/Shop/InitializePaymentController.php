<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop;

use BitBag\SyliusIngPlugin\Bus\Command\SaveTransaction;
use BitBag\SyliusIngPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPlugin\Bus\Query\GetBlikTransactionData;
use BitBag\SyliusIngPlugin\Bus\Query\GetTransactionData;
use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Exception\IngNotConfiguredException;
use BitBag\SyliusIngPlugin\Model\Payment\PaymentDataModelInterface;
use BitBag\SyliusIngPlugin\Provider\BlikModel\BlikModelProviderInterface;
use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\Payment\OrderPaymentResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\Payment\TransactionPaymentDataResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class InitializePaymentController
{
    private OrderResolverInterface $orderResolver;

    private OrderPaymentResolverInterface $paymentResolver;

    private DispatcherInterface $dispatcher;

    private BlikModelProviderInterface $blikModelProvider;

    private TransactionPaymentDataResolverInterface $transactionPaymentDataResolver;

    public function __construct(
        OrderResolverInterface $orderResolver,
        OrderPaymentResolverInterface $paymentResolver,
        DispatcherInterface $dispatcher,
        BlikModelProviderInterface $blikModelProvider,
        TransactionPaymentDataResolverInterface $transactionPaymentDataResolver
    ) {
        $this->orderResolver = $orderResolver;
        $this->paymentResolver = $paymentResolver;
        $this->dispatcher = $dispatcher;
        $this->blikModelProvider = $blikModelProvider;
        $this->transactionPaymentDataResolver = $transactionPaymentDataResolver;
    }

    public function __invoke(
        Request $request,
        ?string $orderId,
        ?string $paymentMethodCode,
        ?string $blikCode
    ): Response {
        $order = $this->orderResolver->resolve($orderId);

        try {
            $payment = $this->paymentResolver->resolve($order);
        } catch (\InvalidArgumentException $e) {
            throw new IngNotConfiguredException('Payment method not found');
        }

        $transactionPaymentData = $this->transactionPaymentDataResolver->resolve($paymentMethodCode, $payment, $blikCode);
        $isBlik = 'blik' === $transactionPaymentData->getPaymentMethod();

        $transactionData = $isBlik ? $this->getTransactionDataForBlik($order, $payment, $transactionPaymentData, $blikCode)
            : $this->getTransactionData($order, $payment, $transactionPaymentData);

        $this->dispatcher->dispatch(new SaveTransaction($transactionData));

        return new RedirectResponse($transactionData->getPaymentUrl());
    }

    private function getTransactionData(
        OrderInterface $order,
        PaymentInterface $payment,
        PaymentDataModelInterface $transactionPaymentData
    ): IngTransactionInterface {
        return $this->dispatcher->dispatch(
            new GetTransactionData(
                $order,
                $payment->getMethod()->getCode(),
                $transactionPaymentData->getPaymentMethod(),
                $transactionPaymentData->getPaymentMethodCode(),
            )
        );
    }

    private function getTransactionDataForBlik(
        OrderInterface $order,
        PaymentInterface $payment,
        PaymentDataModelInterface $transactionPaymentData,
        ?string $blikCode
    ): IngTransactionInterface {
        $blikModel = $this->blikModelProvider->provideDataToBlikModel($blikCode);

        return $this->dispatcher->dispatch(
            new GetBlikTransactionData(
                $order,
                $payment->getMethod()->getCode(),
                $transactionPaymentData->getPaymentMethod(),
                $transactionPaymentData->getPaymentMethodCode(),
                $blikModel
            )
        );
    }
}
