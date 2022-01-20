<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\UrlResolver;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;

interface IngUrlResolverInterface
{
    public function buildUrl(string $code, IngClientConfigurationInterface $clientConfiguration): string;
}
