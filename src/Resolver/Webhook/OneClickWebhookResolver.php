<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Webhook;

use BitBag\SyliusIngPlugin\Bus\Command\SaveTransaction;
use BitBag\SyliusIngPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use BitBag\SyliusIngPlugin\Factory\Transaction\IngTransactionFactoryInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Resolver\Payment\OrderPaymentResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\PaymentMethod\PaymentMethodResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class OneClickWebhookResolver implements oneClickWebhookResolverInterface
{
    private RequestStack $requestStack;

    private IngTransactionFactoryInterface $ingTransactionFactory;

    private OrderRepositoryInterface $orderRepository;

    private IngClientConfigurationProviderInterface $ingClientConfigurationProvider;

    private DispatcherInterface $dispatcher;

    private PaymentMethodResolverInterface $paymentMethodResolver;

    private OrderPaymentResolverInterface $orderPaymentResolver;

    public function __construct(
        RequestStack $requestStack,
        IngTransactionFactoryInterface $ingTransactionFactory,
        OrderRepositoryInterface $orderRepository,
        IngClientConfigurationProviderInterface $ingClientConfigurationProvider,
        DispatcherInterface $dispatcher,
        PaymentMethodResolverInterface $paymentMethodResolver,
        OrderPaymentResolverInterface $orderPaymentResolver
    ) {
        $this->requestStack = $requestStack;
        $this->ingTransactionFactory = $ingTransactionFactory;
        $this->orderRepository = $orderRepository;
        $this->ingClientConfigurationProvider = $ingClientConfigurationProvider;
        $this->dispatcher = $dispatcher;
        $this->paymentMethodResolver = $paymentMethodResolver;
        $this->orderPaymentResolver = $orderPaymentResolver;
    }

    public function resolve(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        $content = \json_decode($request->getContent(), true);
        $transactionPayload = $content['transaction'] ?? [];

        if (null === $transactionPayload['paymentProfile']) {
            return false;
        }

        $transactionId = $transactionPayload['id'] ?? '';
        $paymentMethod = $transactionPayload['paymentMethod'] ?? '';
        $paymentMethodCode = $transactionPayload['paymentMethodCode'] ?? '';
        $orderId = $transactionPayload['orderId'] ?? '';

        if ('' === $transactionId || '' === $paymentMethod || '' === $orderId || '' === $paymentMethodCode) {
            throw new IngBadRequestException('No valid transaction data could be found');
        }

        if ('card' === $paymentMethod && 'oneclick' === $paymentMethodCode) {
            /** @var OrderInterface $order */
            $order = $this->orderRepository->find($orderId);
            $payment = $this->orderPaymentResolver->resolve($order);
            $gatewayCode = $this->paymentMethodResolver->resolve($payment)->getCode();
            $config = $this->ingClientConfigurationProvider->getPaymentMethodConfiguration($gatewayCode);
            $transaction = $this->ingTransactionFactory->create(
                $payment,
                $transactionId,
                null,
                $config->getServiceId(),
                $orderId,
                $gatewayCode
            );
            $this->dispatcher->dispatch(new SaveTransaction($transaction));
        }

        return true;
    }
}
