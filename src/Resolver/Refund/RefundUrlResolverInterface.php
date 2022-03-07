<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Refund;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;

interface RefundUrlResolverInterface
{
    public function resolve(IngClientConfigurationInterface $config, int $paymentId): string;
}
