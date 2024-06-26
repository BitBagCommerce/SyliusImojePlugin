<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\GatewayFactoryName;

interface GatewayFactoryNameResolverInterface
{
    public function resolve(string $gatewayCode): string;
}
