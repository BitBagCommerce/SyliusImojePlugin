<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\IngOneClickSignature;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;

interface IngOneClickSignatureResolverInterface
{
    public function resolve(array $orderData, IngClientConfigurationInterface $config): string;
}
