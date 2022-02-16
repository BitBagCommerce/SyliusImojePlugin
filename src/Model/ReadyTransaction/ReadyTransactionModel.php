<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model\ReadyTransaction;

use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;

final class ReadyTransactionModel implements ReadyTransactionModelInterface
{
    private string $status;

    private IngTransactionInterface $ingTransaction;

    public function __construct(string $status, IngTransactionInterface $ingTransaction)
    {
        $this->status = $status;
        $this->ingTransaction = $ingTransaction;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getIngTransaction(): IngTransactionInterface
    {
        return $this->ingTransaction;
    }
}
