<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Provider;

use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;

interface IngPayClientConfigurationProviderInterface
{
    public const FACTORY_NAME = 'BitBag_ing_pay';

    public function getPaymentMethodConfiguration(string $code): IngPayClientConfigurationInterface;
}
