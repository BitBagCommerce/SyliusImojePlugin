<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Command;

use BitBag\SyliusImojePlugin\Entity\IngTransactionInterface;

final class SaveTransaction
{
    private IngTransactionInterface $ingTransaction;

    public function __construct(IngTransactionInterface $ingTransaction)
    {
        $this->ingTransaction = $ingTransaction;
    }

    public function getIngTransaction(): IngTransactionInterface
    {
        return $this->ingTransaction;
    }
}
