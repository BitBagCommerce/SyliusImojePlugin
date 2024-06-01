<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider;

use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;

interface ImojeClientConfigurationProviderInterface
{
    public const FACTORY_NAME = 'BitBag_imoje';

    public function getPaymentMethodConfiguration(string $code): ImojeClientConfigurationInterface;
}
