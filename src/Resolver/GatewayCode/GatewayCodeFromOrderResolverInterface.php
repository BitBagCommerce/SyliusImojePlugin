<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\GatewayCode;

use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface GatewayCodeFromOrderResolverInterface
{
    public function resolve(OrderInterface $order): ImojeClientConfigurationInterface;
}
