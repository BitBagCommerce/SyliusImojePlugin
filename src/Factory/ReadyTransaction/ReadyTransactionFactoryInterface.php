<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\ReadyTransaction;

use BitBag\SyliusImojePlugin\Entity\IngTransactionInterface;
use BitBag\SyliusImojePlugin\Model\ReadyTransaction\ReadyTransactionModel;
use Sylius\Component\Core\Model\OrderInterface;

interface ReadyTransactionFactoryInterface
{
    public function createReadyTransaction(
        string $contents,
        IngTransactionInterface $ingTransaction,
        OrderInterface $order
    ): ReadyTransactionModel;
}
