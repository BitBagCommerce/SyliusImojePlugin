<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Repository\Order;

use Sylius\Component\Core\Model\OrderInterface;

interface OrderRepositoryInterface
{
    public function findByTokenValue(string $tokenValue): ?OrderInterface;
}
