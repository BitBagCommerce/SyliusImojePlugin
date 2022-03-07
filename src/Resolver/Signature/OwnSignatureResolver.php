<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Signature;

use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Resolver\GatewayCode\GatewayCodeResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class OwnSignatureResolver implements OwnSignatureResolverInterface
{
    private RequestStack $requestStack;

    private GatewayCodeResolverInterface $gatewayCodeResolver;

    private IngClientConfigurationProviderInterface $configurationProvider;

    public function __construct(
        RequestStack $requestStack,
        GatewayCodeResolverInterface $gatewayCodeResolver,
        IngClientConfigurationProviderInterface $configurationProvider
    ) {
        $this->requestStack = $requestStack;
        $this->gatewayCodeResolver = $gatewayCodeResolver;
        $this->configurationProvider = $configurationProvider;
    }

    public function resolve(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $body = $request->getContent();
        $code = $this->gatewayCodeResolver->resolve(IngClientConfigurationProviderInterface::FACTORY_NAME);
        $config = $this->configurationProvider->getPaymentMethodConfiguration($code);

        return \sprintf('%s%s', $body, $config->getShopKey());
    }
}
