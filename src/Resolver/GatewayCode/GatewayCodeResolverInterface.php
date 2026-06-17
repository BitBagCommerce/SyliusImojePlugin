<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\GatewayCode;

interface GatewayCodeResolverInterface
{
    public function resolve(string $factoryName): string;
}
