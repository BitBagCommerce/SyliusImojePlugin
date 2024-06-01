<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Signature;

use BitBag\SyliusImojePlugin\Provider\ImojeClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Resolver\GatewayCode\GatewayCodeResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class OwnSignatureResolver implements OwnSignatureResolverInterface
{
    private RequestStack $requestStack;

    private GatewayCodeResolverInterface $gatewayCodeResolver;

    private ImojeClientConfigurationProviderInterface $configurationProvider;

    public function __construct(
        RequestStack $requestStack,
        GatewayCodeResolverInterface $gatewayCodeResolver,
        ImojeClientConfigurationProviderInterface $configurationProvider
    ) {
        $this->requestStack = $requestStack;
        $this->gatewayCodeResolver = $gatewayCodeResolver;
        $this->configurationProvider = $configurationProvider;
    }

    public function resolve(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $body = $request->getContent();
        $code = $this->gatewayCodeResolver->resolve(ImojeClientConfigurationProviderInterface::FACTORY_NAME);
        $config = $this->configurationProvider->getPaymentMethodConfiguration($code);

        return \sprintf('%s%s', $body, $config->getShopKey());
    }
}
