<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Refund;

use BitBag\SyliusImojePlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\IngClientProviderInterface;
use BitBag\SyliusImojePlugin\Resolver\GatewayFactoryName\GatewayFactoryNameResolverInterface;
use BitBag\SyliusImojePlugin\Resolver\Refund\RefundUrlResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\RefundPlugin\Event\UnitsRefunded;
use Sylius\RefundPlugin\ProcessManager\UnitsRefundedProcessStepInterface;

final class IngRefundStep implements UnitsRefundedProcessStepInterface
{
    private IngClientProviderInterface $ingClientProvider;

    private OrderRepositoryInterface $orderRepository;

    private GatewayFactoryNameResolverInterface $gatewayFactoryNameResolver;

    private IngClientConfigurationProviderInterface $ingClientConfigurationProvider;

    private RefundUrlResolverInterface $refundUrlResolver;

    public function __construct(
        IngClientProviderInterface $ingClientProvider,
        OrderRepositoryInterface $orderRepository,
        GatewayFactoryNameResolverInterface $gatewayFactoryNameResolver,
        IngClientConfigurationProviderInterface $ingClientConfigurationProvider,
        RefundUrlResolverInterface $refundUrlResolver
    ) {
        $this->ingClientProvider = $ingClientProvider;
        $this->orderRepository = $orderRepository;
        $this->gatewayFactoryNameResolver = $gatewayFactoryNameResolver;
        $this->ingClientConfigurationProvider = $ingClientConfigurationProvider;
        $this->refundUrlResolver = $refundUrlResolver;
    }

    public function next(UnitsRefunded $unitsRefunded): void
    {
        /** @var OrderInterface $order */
        $order = $this->orderRepository->findOneBy(['number' => $unitsRefunded->orderNumber()]);
        /** @var PaymentInterface $payment */
        $payment = $order->getLastPayment();
        $gatewayCode = $payment->getMethod()->getCode();
        $gatewayFactory = $this->gatewayFactoryNameResolver->resolve($gatewayCode);

        if (IngClientConfigurationProviderInterface::FACTORY_NAME !== $gatewayFactory) {
            return;
        }
        $config = $this->ingClientConfigurationProvider->getPaymentMethodConfiguration($gatewayCode);
        $url = $this->refundUrlResolver->resolve($config, $payment->getId());
        $client = $this->ingClientProvider->getClient($gatewayCode);

        $client->refundTransaction($url, $config->getServiceId(), $unitsRefunded->amount());
    }
}
