<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\GatewayCode;

interface GatewayCodeResolverInterface
{
    public function resolve(string $factoryName): string;
}
