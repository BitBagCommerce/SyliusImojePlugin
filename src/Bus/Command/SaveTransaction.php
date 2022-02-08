<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Command;

use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;

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
