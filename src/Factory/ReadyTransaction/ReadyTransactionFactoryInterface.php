<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\ReadyTransaction;

use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Model\ReadyTransaction\ReadyTransactionModel;
use Sylius\Component\Core\Model\OrderInterface;

interface ReadyTransactionFactoryInterface
{
    public function createReadyTransaction(
        string $contents,
        ImojeTransactionInterface $imojeTransaction,
        OrderInterface $order,
    ): ReadyTransactionModel;
}
