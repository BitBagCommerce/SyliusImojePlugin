<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\IngOneClickSignature;

use BitBag\SyliusImojePlugin\Configuration\IngClientConfigurationInterface;

interface IngOneClickSignatureResolverInterface
{
    public function resolve(array $orderData, IngClientConfigurationInterface $config): string;
}
