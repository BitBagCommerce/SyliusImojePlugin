<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Provider;

use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;

interface IngClientConfigurationProviderFactoryInterface
{
    public function createIngClientConfigurationProvider(): IngClientConfigurationProviderInterface;
}
