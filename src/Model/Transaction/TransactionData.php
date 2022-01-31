<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model\Transaction;

final class TransactionData implements TransactionDataInterface
{
    /** @var string */
    private $transactionId;

    /** @var string */
    private $redirectUrl;

    public function __construct(string $transactionId, ?string $redirectUrl)
    {
        $this->transactionId = $transactionId;
        $this->redirectUrl = $redirectUrl;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }
}
