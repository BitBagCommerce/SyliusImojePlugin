<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\GatewayCode;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface GatewayCodeFromOrderResolverInterface
{
    public function resolve(OrderInterface $order): IngClientConfigurationInterface;
}
