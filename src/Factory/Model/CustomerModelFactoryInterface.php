<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Model\CustomerModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface CustomerModelFactoryInterface
{
    public function createCustomerModel(OrderInterface $order): CustomerModelInterface;
}
