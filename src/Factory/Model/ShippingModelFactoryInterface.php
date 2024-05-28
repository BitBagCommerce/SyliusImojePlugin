<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Model;

use BitBag\SyliusImojePlugin\Model\ShippingModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface ShippingModelFactoryInterface
{
    public function create(OrderInterface $order): ShippingModelInterface;
}
