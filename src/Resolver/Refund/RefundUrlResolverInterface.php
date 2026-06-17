<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Refund;

use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;

interface RefundUrlResolverInterface
{
    public function resolve(IngPayClientConfigurationInterface $config, int $paymentId): string;
}
