<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\GatewayCode;

use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface GatewayCodeFromOrderResolverInterface
{
    public function resolve(OrderInterface $order): IngPayClientConfigurationInterface;
}
