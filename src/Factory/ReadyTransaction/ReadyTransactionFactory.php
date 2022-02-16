<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\ReadyTransaction;

use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Model\ReadyTransaction\ReadyTransactionModel;

final class ReadyTransactionFactory implements ReadyTransactionFactoryInterface
{
    public function createReadyTransaction(string $status, IngTransactionInterface $ingTransaction): ReadyTransactionModel
    {
        return new ReadyTransactionModel($status, $ingTransaction);
    }
}
