<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\ImojeOneClickSignature;

use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;

interface ImojeOneClickSignatureResolverInterface
{
    public function resolve(array $orderData, ImojeClientConfigurationInterface $config): string;
}
