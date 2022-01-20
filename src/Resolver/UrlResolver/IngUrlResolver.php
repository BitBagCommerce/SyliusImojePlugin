<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\UrlResolver;

use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;

final class IngUrlResolver implements IngUrlResolverInterface
{
    public function buildUrl(string $code, IngClientConfigurationProviderInterface $clientConfigurationProvider): string
    {
        $baseUrl = '';

        $clientConfiguration = $clientConfigurationProvider->getPaymentMethodConfiguration($code);

        if ($clientConfiguration->isProd() === true) {
            $baseUrl = $clientConfiguration->getProdUrl();
        } else {
            $baseUrl = $clientConfiguration->getSandboxUrl();
        }

        return sprintf('%s/%s/', $baseUrl, $clientConfiguration->getMerchantId());
    }
}
