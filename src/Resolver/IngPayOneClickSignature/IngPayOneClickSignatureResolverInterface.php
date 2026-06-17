<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\IngPayOneClickSignature;

use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;

interface IngPayOneClickSignatureResolverInterface
{
    public function resolve(array $orderData, IngPayClientConfigurationInterface $config): string;
}
