<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\ReadyTransaction;

use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Model\ReadyTransaction\ReadyTransactionModel;
use Sylius\Component\Core\Model\OrderInterface;

final class ReadyTransactionFactory implements ReadyTransactionFactoryInterface
{
    public function createReadyTransaction(
        string $status,
        IngTransactionInterface $ingTransaction,
        OrderInterface $order
    ): ReadyTransactionModel
    {
        return new ReadyTransactionModel($status, $ingTransaction, $order);
    }
}
