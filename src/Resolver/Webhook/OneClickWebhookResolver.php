<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Webhook;

use BitBag\SyliusImojePlugin\Bus\Command\SaveTransaction;
use BitBag\SyliusImojePlugin\Bus\DispatcherInterface;
use BitBag\SyliusImojePlugin\Exception\ImojeBadRequestException;
use BitBag\SyliusImojePlugin\Factory\Status\StatusResponseModelFactoryInterface;
use BitBag\SyliusImojePlugin\Factory\Transaction\ImojeTransactionFactoryInterface;
use BitBag\SyliusImojePlugin\Processor\Webhook\Status\WebhookResponseProcessorInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Resolver\Payment\OrderPaymentResolverInterface;
use BitBag\SyliusImojePlugin\Resolver\PaymentMethod\PaymentMethodResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class OneClickWebhookResolver implements oneClickWebhookResolverInterface
{
    private RequestStack $requestStack;

    private ImojeTransactionFactoryInterface $imojeTransactionFactory;

    private OrderRepositoryInterface $orderRepository;

    private ImojeClientConfigurationProviderInterface $imojeClientConfigurationProvider;

    private DispatcherInterface $dispatcher;

    private PaymentMethodResolverInterface $paymentMethodResolver;

    private OrderPaymentResolverInterface $orderPaymentResolver;

    private StatusResponseModelFactoryInterface $statusResponseModelFactory;

    private WebhookResponseProcessorInterface $webhookResponseProcessor;

    public function __construct(
        RequestStack $requestStack,
        ImojeTransactionFactoryInterface $imojeTransactionFactory,
        OrderRepositoryInterface $orderRepository,
        ImojeClientConfigurationProviderInterface $imojeClientConfigurationProvider,
        DispatcherInterface $dispatcher,
        PaymentMethodResolverInterface $paymentMethodResolver,
        OrderPaymentResolverInterface $orderPaymentResolver,
        StatusResponseModelFactoryInterface $statusResponseModelFactory,
        WebhookResponseProcessorInterface $webhookResponseProcessor,
    ) {
        $this->requestStack = $requestStack;
        $this->imojeTransactionFactory = $imojeTransactionFactory;
        $this->orderRepository = $orderRepository;
        $this->imojeClientConfigurationProvider = $imojeClientConfigurationProvider;
        $this->dispatcher = $dispatcher;
        $this->paymentMethodResolver = $paymentMethodResolver;
        $this->orderPaymentResolver = $orderPaymentResolver;
        $this->statusResponseModelFactory = $statusResponseModelFactory;
        $this->webhookResponseProcessor = $webhookResponseProcessor;
    }

    public function resolve(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        $content = \json_decode($request->getContent(), true);
        $transactionPayload = $content['transaction'] ?? [];
        $paymentProfile = $transactionPayload['paymentProfile'] ?? '';

        if ('' === $paymentProfile) {
            return false;
        }
        $transactionId = $transactionPayload['id'] ?? '';
        $paymentMethod = $transactionPayload['paymentMethod'] ?? '';
        $paymentMethodCode = $transactionPayload['paymentMethodCode'] ?? '';
        $orderId = $transactionPayload['orderId'] ?? '';
        $transactionStatus = $transactionPayload['status'] ?? '';

        $data = [$transactionId, $paymentMethod, $orderId, $paymentMethodCode, $transactionStatus];

        foreach ($data as $item) {
            if ('' === $item) {
                throw new ImojeBadRequestException('Missing mandatory transaction data');
            }
        }

        if ('card' === $paymentMethod && 'oneclick' === $paymentMethodCode) {
            /** @var OrderInterface $order */
            $order = $this->orderRepository->find($orderId);
            $payment = $this->orderPaymentResolver->resolve($order);
            $paymentId = $payment->getId();
            $gatewayCode = $this->paymentMethodResolver->resolve($payment)->getCode();
            $config = $this->imojeClientConfigurationProvider->getPaymentMethodConfiguration($gatewayCode);
            $transaction = $this->imojeTransactionFactory->create(
                $payment,
                $transactionId,
                null,
                $config->getServiceId(),
                $orderId,
                $gatewayCode,
            );

            $this->dispatcher->dispatch(new SaveTransaction($transaction));
            $webhookModel = $this->statusResponseModelFactory->create($transactionId, (string) $paymentId, (string) $orderId, $transactionStatus);
            $this->webhookResponseProcessor->process($webhookModel, $payment);
        }

        return true;
    }
}
