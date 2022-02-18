<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\ReadyTransaction;

use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Model\ReadyTransaction\ReadyTransactionModel;
use Sylius\Component\Core\Model\OrderInterface;

interface ReadyTransactionFactoryInterface
{
    public function createReadyTransaction(
        string $contents,
        IngTransactionInterface $ingTransaction,
        OrderInterface $order
    ): ReadyTransactionModel;
}
