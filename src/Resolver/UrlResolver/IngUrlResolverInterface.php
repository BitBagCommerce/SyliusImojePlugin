<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\UrlResolver;

use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;

interface IngUrlResolverInterface
{
    public function buildUrl(string $code, IngClientConfigurationProviderInterface $clientConfigurationProvider): string;
}
