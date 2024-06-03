<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Refund;

use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;

interface RefundUrlResolverInterface
{
    public function resolve(ImojeClientConfigurationInterface $config, int $paymentId): string;
}
