<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop;

use BitBag\SyliusIngPlugin\Bus\Command\TakeOverPayment;
use BitBag\SyliusIngPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPlugin\Bus\Query\GetTransactionData;
use BitBag\SyliusIngPlugin\Exception\IngNotConfiguredException;
use BitBag\SyliusIngPlugin\Factory\Payment\PaymentMethodAndCodeModelFactoryInterface;
use BitBag\SyliusIngPlugin\Model\Transaction\TransactionDataInterface;
use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\Payment\OrderPaymentResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class InitializePaymentController
{
    private OrderResolverInterface $orderResolver;

    private OrderPaymentResolverInterface $paymentResolver;

    private UrlGeneratorInterface $urlGenerator;

    private DispatcherInterface $dispatcher;

    private PaymentMethodAndCodeModelFactoryInterface $paymentMethodAndCodeModelFactory;

    public function __construct(
        OrderResolverInterface $orderResolver,
        OrderPaymentResolverInterface $paymentResolver,
        UrlGeneratorInterface $urlGenerator,
        DispatcherInterface $dispatcher,
        PaymentMethodAndCodeModelFactoryInterface $paymentMethodAndCodeModelFactory
    ) {
        $this->orderResolver = $orderResolver;
        $this->paymentResolver = $paymentResolver;
        $this->urlGenerator = $urlGenerator;
        $this->dispatcher = $dispatcher;
        $this->paymentMethodAndCodeModelFactory = $paymentMethodAndCodeModelFactory;
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

        $transactionPaymentData = $this->paymentMethodAndCodeModelFactory->create($payment);

        /** @var TransactionDataInterface $transactionData */
        $transactionData = $this->dispatcher->dispatch(
            new GetTransactionData(
                $order,
                $payment->getMethod()->getCode(),
                $transactionPaymentData->getPaymentMethod(),
                $transactionPaymentData->getPaymentMethodCode()
            )
        );
        return new Response();
    }
}
