<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Signature;

use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Resolver\GatewayCode\GatewayCodeResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\TransactionId\TransactionIdResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class OwnSignatureResolver implements OwnSignatureResolverInterface
{
    private RequestStack $requestStack;

    private GatewayCodeResolverInterface $gatewayCodeResolver;

    private TransactionIdResolverInterface $transactionIdResolver;

    private IngClientConfigurationProviderInterface $configurationProvider;

    public function __construct(
        RequestStack $requestStack,
        GatewayCodeResolverInterface $gatewayCodeResolver,
        TransactionIdResolverInterface $transactionIdResolver,
        IngClientConfigurationProviderInterface $configurationProvider
    ) {
        $this->requestStack = $requestStack;
        $this->gatewayCodeResolver = $gatewayCodeResolver;
        $this->transactionIdResolver = $transactionIdResolver;
        $this->configurationProvider = $configurationProvider;
    }

    public function resolve(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $body = $request->getContent();
        $transactionId = $this->transactionIdResolver->resolve($body);
        $code = $this->gatewayCodeResolver->resolve($transactionId);
        $config = $this->configurationProvider->getPaymentMethodConfiguration($code);

        return \sprintf('%s%s', $body, $config->getShopKey());
    }
}
