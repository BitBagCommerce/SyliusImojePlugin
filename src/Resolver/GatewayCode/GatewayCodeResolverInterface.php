<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\GatewayCode;

interface GatewayCodeResolverInterface
{
    public function resolve(string $factoryName): string;
}
