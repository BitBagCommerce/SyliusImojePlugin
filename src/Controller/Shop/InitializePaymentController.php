<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop;

use BitBag\SyliusIngPlugin\Bus\Command\TakeOverPayment;
use BitBag\SyliusIngPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPlugin\Bus\Query\GetTransactionData;
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

    public function __construct(
        OrderResolverInterface $orderResolver,
        OrderPaymentResolverInterface $paymentResolver,
        UrlGeneratorInterface $urlGenerator,
        DispatcherInterface $dispatcher
    ) {
        $this->orderResolver = $orderResolver;
        $this->paymentResolver = $paymentResolver;
        $this->urlGenerator = $urlGenerator;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(Request $request): Response
    {
        $code = $request->query->get('code');
        $order = $this->orderResolver->resolve();

        try {
            $payment = $this->paymentResolver->resolve($order);
        } catch (\InvalidArgumentException $e) {
            return new Response();
        }

        if ($code !== null) {
            $this->dispatcher->dispatch(new TakeOverPayment($payment, $code));
        }
        /** @var TransactionDataInterface $transactionData */
        $transactionData = $this->dispatcher->dispatch(new GetTransactionData($order, $payment->getMethod()->getCode()));

        return new Response();
    }
}
