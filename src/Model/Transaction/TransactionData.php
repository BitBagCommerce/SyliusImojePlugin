<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model\Transaction;

final class TransactionData implements TransactionDataInterface
{
    private string $transactionId;

    private string $paymentUrl;

    public function __construct(string $transactionId, string $paymentUrl)
    {
        $this->transactionId = $transactionId;
        $this->paymentUrl = $paymentUrl;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
    }
}
