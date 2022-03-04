<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Webhook;

use BitBag\SyliusIngPlugin\Bus\Command\SaveTransaction;
use BitBag\SyliusIngPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use BitBag\SyliusIngPlugin\Factory\Status\StatusResponseModelFactoryInterface;
use BitBag\SyliusIngPlugin\Factory\Transaction\IngTransactionFactoryInterface;
use BitBag\SyliusIngPlugin\Model\Status\StatusResponseModelInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class WebhookResolver implements WebhookResolverInterface
{
    private RequestStack $requestStack;

    private StatusResponseModelFactoryInterface $statusResponseModelFactory;

    private IngTransactionFactoryInterface $ingTransactionFactory;

    private OrderRepositoryInterface $orderRepository;

    private IngClientConfigurationProviderInterface $ingClientConfigurationProvider;

    private DispatcherInterface $dispatcher;

    public function __construct(
        RequestStack $requestStack,
        StatusResponseModelFactoryInterface $statusResponseModelFactory,
        IngTransactionFactoryInterface $ingTransactionFactory,
        OrderRepositoryInterface $orderRepository,
        IngClientConfigurationProviderInterface $ingClientConfigurationProvider,
        DispatcherInterface $dispatcher
    )
    {
        $this->requestStack = $requestStack;
        $this->statusResponseModelFactory = $statusResponseModelFactory;
        $this->ingTransactionFactory = $ingTransactionFactory;
        $this->orderRepository = $orderRepository;
        $this->ingClientConfigurationProvider = $ingClientConfigurationProvider;
        $this->dispatcher = $dispatcher;
    }

    public function resolve(): StatusResponseModelInterface
    {
        $request = $this->requestStack->getCurrentRequest();

        $content = \json_decode($request->getContent(), true);

        if (null === $content['transaction'] || null === $content['payment']) {
            throw new IngBadRequestException('No found data in request');
        }

        $transactionId = $content['transaction']['id'] ?? '';
        $paymentMethod = $content['transaction']['paymentMethod'] ?? '';
        $paymentMethodCode = $content['transaction']['paymentMethodCode'] ?? '';
        $paymentId = $content['payment']['id'] ?? '';
        $orderId = $content['transaction']['orderId'] ?? '';
        $transactionStatus = $content['transaction']['status'] ?? '';

        if ('' === $transactionId || '' === $orderId) {
            throw new IngBadRequestException('No found data in request');
        }

        if ('card' === $paymentMethod && 'oneclick' === $paymentMethodCode) {
            /** @var OrderInterface $order */
            $order = $this->orderRepository->find($orderId);
            $payment = $order->getLastPayment();
            $gatewayCode = $payment->getMethod()->getCode();
            $config = $this->ingClientConfigurationProvider->getPaymentMethodConfiguration($gatewayCode);
            $transaction = $this->ingTransactionFactory->create(
                $payment,
                $transactionId,
                'By card',
                $config->getServiceId(),
                $orderId,
                $gatewayCode
            );
            $this->dispatcher->dispatch(new SaveTransaction($transaction));
        }

        if ('' === $paymentId || '' === $transactionStatus) {
            throw new IngBadRequestException('No found data in request');
        }

        return $this->statusResponseModelFactory->create($transactionId, $paymentId, $orderId, $transactionStatus);
    }
}
