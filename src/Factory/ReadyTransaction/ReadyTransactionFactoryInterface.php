<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\ReadyTransaction;

use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use BitBag\SyliusIngPayPlugin\Model\ReadyTransaction\ReadyTransactionModel;
use Sylius\Component\Core\Model\OrderInterface;

interface ReadyTransactionFactoryInterface
{
    public function createReadyTransaction(
        string $contents,
        IngPayTransactionInterface $ingPayTransaction,
        OrderInterface $order,
    ): ReadyTransactionModel;
}
