<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model\Transaction;

interface TransactionDataInterface
{
    public function getTransactionId(): string;

    public function getPaymentUrl(): ?string;
}
