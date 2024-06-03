<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Order;

use Sylius\Component\Core\Model\OrderInterface;

interface OrderResolverInterface
{
    public function resolve(?string $orderId = null): OrderInterface;
}
