<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Webhook;

use BitBag\SyliusIngPayPlugin\Bus\Command\SaveTransaction;
use BitBag\SyliusIngPayPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPayPlugin\Exception\IngPayBadRequestException;
use BitBag\SyliusIngPayPlugin\Factory\Status\StatusResponseModelFactoryInterface;
use BitBag\SyliusIngPayPlugin\Factory\Transaction\IngPayTransactionFactoryInterface;
use BitBag\SyliusIngPayPlugin\Processor\Webhook\Status\WebhookResponseProcessorInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientConfigurationProviderInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Payment\OrderPaymentResolverInterface;
use BitBag\SyliusIngPayPlugin\Resolver\PaymentMethod\PaymentMethodResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class OneClickWebhookResolver implements oneClickWebhookResolverInterface
{
    private RequestStack $requestStack;

    private IngPayTransactionFactoryInterface $ingPayTransactionFactory;

    private OrderRepositoryInterface $orderRepository;

    private IngPayClientConfigurationProviderInterface $ingPayClientConfigurationProvider;

    private DispatcherInterface $dispatcher;

    private PaymentMethodResolverInterface $paymentMethodResolver;

    private OrderPaymentResolverInterface $orderPaymentResolver;

    private StatusResponseModelFactoryInterface $statusResponseModelFactory;

    private WebhookResponseProcessorInterface $webhookResponseProcessor;

    public function __construct(
        RequestStack $requestStack,
        IngPayTransactionFactoryInterface $ingPayTransactionFactory,
        OrderRepositoryInterface $orderRepository,
        IngPayClientConfigurationProviderInterface $ingPayClientConfigurationProvider,
        DispatcherInterface $dispatcher,
        PaymentMethodResolverInterface $paymentMethodResolver,
        OrderPaymentResolverInterface $orderPaymentResolver,
        StatusResponseModelFactoryInterface $statusResponseModelFactory,
        WebhookResponseProcessorInterface $webhookResponseProcessor,
    ) {
        $this->requestStack = $requestStack;
        $this->ingPayTransactionFactory = $ingPayTransactionFactory;
        $this->orderRepository = $orderRepository;
        $this->ingPayClientConfigurationProvider = $ingPayClientConfigurationProvider;
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
                throw new IngPayBadRequestException('Missing mandatory transaction data');
            }
        }

        if ('card' === $paymentMethod && 'oneclick' === $paymentMethodCode) {
            /** @var OrderInterface $order */
            $order = $this->orderRepository->find($orderId);
            $payment = $this->orderPaymentResolver->resolve($order);
            $paymentId = $payment->getId();
            $gatewayCode = $this->paymentMethodResolver->resolve($payment)->getCode();
            $config = $this->ingPayClientConfigurationProvider->getPaymentMethodConfiguration($gatewayCode);
            $transaction = $this->ingPayTransactionFactory->create(
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
