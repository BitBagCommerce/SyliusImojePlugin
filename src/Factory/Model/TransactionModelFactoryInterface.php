<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Model\TransactionModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface TransactionModelFactoryInterface
{
    public function createTransactionModel(
        OrderInterface $order,
        IngClientConfigurationInterface $ingClientConfiguration,
        string $type, string $paymentMethod,
        string $paymentMethodCode
    ): TransactionModelInterface;
}
