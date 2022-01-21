<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Order;

use Sylius\Component\Core\Model\OrderInterface;

interface OrderResolverInterface
{
    public function resolve(?string $tokenValue = null): OrderInterface;
}
