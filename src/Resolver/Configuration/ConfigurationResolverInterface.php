<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Configuration;

interface ConfigurationResolverInterface
{
    public function resolve(array $config): array;
}
