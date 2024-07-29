<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\GatewayCode;

use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Resolver\Payment\OrderPaymentResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class GatewayCodeFromOrderResolver implements GatewayCodeFromOrderResolverInterface
{
    private ImojeClientConfigurationProviderInterface $imojeClientConfigurationProvider;

    private OrderPaymentResolverInterface $orderPaymentResolver;

    public function __construct(
        ImojeClientConfigurationProviderInterface $imojeClientConfigurationProvider,
        OrderPaymentResolverInterface $orderPaymentResolver,
    ) {
        $this->imojeClientConfigurationProvider = $imojeClientConfigurationProvider;
        $this->orderPaymentResolver = $orderPaymentResolver;
    }

    public function resolve(OrderInterface $order): ImojeClientConfigurationInterface
    {
        $payment = $this->orderPaymentResolver->resolve($order);
        $gatewayCode = $payment->getMethod()->getCode();

        return $this->imojeClientConfigurationProvider->getPaymentMethodConfiguration($gatewayCode);
    }
}
