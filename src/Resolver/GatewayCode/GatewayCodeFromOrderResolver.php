<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\GatewayCode;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class GatewayCodeFromOrderResolver implements GatewayCodeFromOrderResolverInterface
{
    private IngClientConfigurationProviderInterface $ingClientConfigurationProvider;

    public function __construct(IngClientConfigurationProviderInterface $ingClientConfigurationProvider)
    {
        $this->ingClientConfigurationProvider = $ingClientConfigurationProvider;
    }

    public function resolve(OrderInterface $order): IngClientConfigurationInterface
    {
        $gatewayCode = $order->getLastPayment()->getMethod()->getCode();

        return $this->ingClientConfigurationProvider->getPaymentMethodConfiguration($gatewayCode);
    }
}
