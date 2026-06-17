<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Repository\Order;

use Sylius\Component\Core\Model\OrderInterface;

interface OrderRepositoryInterface
{
    public function findByTokenValue(string $tokenValue): ?OrderInterface;
}
