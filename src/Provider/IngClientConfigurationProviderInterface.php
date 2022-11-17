<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Provider;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;

interface IngClientConfigurationProviderInterface
{
    public const FACTORY_NAME = 'BitBag_imoje';

    public function getPaymentMethodConfiguration(string $code): IngClientConfigurationInterface;
}
