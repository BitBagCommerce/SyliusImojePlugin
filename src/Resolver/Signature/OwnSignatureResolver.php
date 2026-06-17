<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Signature;

use BitBag\SyliusIngPayPlugin\Provider\IngPayClientConfigurationProviderInterface;
use BitBag\SyliusIngPayPlugin\Resolver\GatewayCode\GatewayCodeResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class OwnSignatureResolver implements OwnSignatureResolverInterface
{
    private RequestStack $requestStack;

    private GatewayCodeResolverInterface $gatewayCodeResolver;

    private IngPayClientConfigurationProviderInterface $configurationProvider;

    public function __construct(
        RequestStack $requestStack,
        GatewayCodeResolverInterface $gatewayCodeResolver,
        IngPayClientConfigurationProviderInterface $configurationProvider,
    ) {
        $this->requestStack = $requestStack;
        $this->gatewayCodeResolver = $gatewayCodeResolver;
        $this->configurationProvider = $configurationProvider;
    }

    public function resolve(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $body = $request->getContent();
        $code = $this->gatewayCodeResolver->resolve(IngPayClientConfigurationProviderInterface::FACTORY_NAME);
        $config = $this->configurationProvider->getPaymentMethodConfiguration($code);

        return \sprintf('%s%s', $body, $config->getShopKey());
    }
}
