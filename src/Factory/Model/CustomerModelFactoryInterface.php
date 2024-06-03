<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Model;

use BitBag\SyliusImojePlugin\Model\CustomerModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface CustomerModelFactoryInterface
{
    public function create(OrderInterface $order): CustomerModelInterface;
}
