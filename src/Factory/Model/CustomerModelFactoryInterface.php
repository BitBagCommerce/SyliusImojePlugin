<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Model;

use BitBag\SyliusIngPayPlugin\Model\CustomerModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface CustomerModelFactoryInterface
{
    public function create(OrderInterface $order): CustomerModelInterface;
}
