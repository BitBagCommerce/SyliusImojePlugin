<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider;

use BitBag\SyliusImojePlugin\Configuration\IngClientConfigurationInterface;

interface IngClientConfigurationProviderInterface
{
    public const FACTORY_NAME = 'BitBag_imoje';

    public function getPaymentMethodConfiguration(string $code): IngClientConfigurationInterface;
}
