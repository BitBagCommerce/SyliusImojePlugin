<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Model;

use BitBag\SyliusImojePlugin\Model\BillingModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface BillingModelFactoryInterface
{
    public function create(OrderInterface $order): BillingModelInterface;
}
