<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\UrlResolver;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;

final class IngUrlResolver implements IngUrlResolverInterface
{
    public function buildUrl(string $code, IngClientConfigurationInterface $clientConfiguration): string
    {
        if ($clientConfiguration->isProd() === true) {
            $baseUrl = $clientConfiguration->getProdUrl();
        } else {
            $baseUrl = $clientConfiguration->getSandboxUrl();
        }

        return \sprintf('%s/%s/', $baseUrl, $clientConfiguration->getMerchantId());
    }
}
