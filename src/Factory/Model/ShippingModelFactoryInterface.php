<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Model;

use BitBag\SyliusIngPayPlugin\Model\ShippingModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface ShippingModelFactoryInterface
{
    public function create(OrderInterface $order): ShippingModelInterface;
}
