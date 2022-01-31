<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Model\BillingModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface BillingModelFactoryInterface
{
    public function create(OrderInterface $order): BillingModelInterface;
}
