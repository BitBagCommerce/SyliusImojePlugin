<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Refund;

use BitBag\SyliusIngPayPlugin\Provider\IngPayClientConfigurationProviderInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientProviderInterface;
use BitBag\SyliusIngPayPlugin\Resolver\GatewayFactoryName\GatewayFactoryNameResolverInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Refund\RefundUrlResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\RefundPlugin\Event\UnitsRefunded;
use Sylius\RefundPlugin\ProcessManager\UnitsRefundedProcessStepInterface;

final class IngPayRefundStep implements UnitsRefundedProcessStepInterface
{
    private IngPayClientProviderInterface $ingPayClientProvider;

    private OrderRepositoryInterface $orderRepository;

    private GatewayFactoryNameResolverInterface $gatewayFactoryNameResolver;

    private IngPayClientConfigurationProviderInterface $ingPayClientConfigurationProvider;

    private RefundUrlResolverInterface $refundUrlResolver;

    public function __construct(
        IngPayClientProviderInterface $ingPayClientProvider,
        OrderRepositoryInterface $orderRepository,
        GatewayFactoryNameResolverInterface $gatewayFactoryNameResolver,
        IngPayClientConfigurationProviderInterface $ingPayClientConfigurationProvider,
        RefundUrlResolverInterface $refundUrlResolver,
    ) {
        $this->ingPayClientProvider = $ingPayClientProvider;
        $this->orderRepository = $orderRepository;
        $this->gatewayFactoryNameResolver = $gatewayFactoryNameResolver;
        $this->ingPayClientConfigurationProvider = $ingPayClientConfigurationProvider;
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

        if (IngPayClientConfigurationProviderInterface::FACTORY_NAME !== $gatewayFactory) {
            return;
        }
        $config = $this->ingPayClientConfigurationProvider->getPaymentMethodConfiguration($gatewayCode);
        $url = $this->refundUrlResolver->resolve($config, $payment->getId());
        $client = $this->ingPayClientProvider->getClient($gatewayCode);

        $client->refundTransaction($url, $config->getServiceId(), $unitsRefunded->amount());
    }
}
