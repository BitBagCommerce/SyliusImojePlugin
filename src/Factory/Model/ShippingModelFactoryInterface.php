<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Model\ShippingModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface ShippingModelFactoryInterface
{
    public function createShippingModel(OrderInterface $order): ShippingModelInterface;
}
