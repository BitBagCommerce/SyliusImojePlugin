<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Refund;

use BitBag\SyliusImojePlugin\Configuration\IngClientConfigurationInterface;

interface RefundUrlResolverInterface
{
    public function resolve(IngClientConfigurationInterface $config, int $paymentId): string;
}
