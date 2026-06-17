<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Model;

use BitBag\SyliusIngPayPlugin\Model\BillingModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface BillingModelFactoryInterface
{
    public function create(OrderInterface $order): BillingModelInterface;
}
