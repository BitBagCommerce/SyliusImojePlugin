<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\GatewayCode;

use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientConfigurationProviderInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Payment\OrderPaymentResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class GatewayCodeFromOrderResolver implements GatewayCodeFromOrderResolverInterface
{
    private IngPayClientConfigurationProviderInterface $ingPayClientConfigurationProvider;

    private OrderPaymentResolverInterface $orderPaymentResolver;

    public function __construct(
        IngPayClientConfigurationProviderInterface $ingPayClientConfigurationProvider,
        OrderPaymentResolverInterface $orderPaymentResolver,
    ) {
        $this->ingPayClientConfigurationProvider = $ingPayClientConfigurationProvider;
        $this->orderPaymentResolver = $orderPaymentResolver;
    }

    public function resolve(OrderInterface $order): IngPayClientConfigurationInterface
    {
        $payment = $this->orderPaymentResolver->resolve($order);
        $gatewayCode = $payment->getMethod()->getCode();

        return $this->ingPayClientConfigurationProvider->getPaymentMethodConfiguration($gatewayCode);
    }
}
