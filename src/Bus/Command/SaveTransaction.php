<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Command;

use Sylius\Component\Core\Model\PaymentInterface;

final class SaveTransaction
{
    private PaymentInterface $payment;

    private string $transactionId;

    public function __construct(PaymentInterface $payment, string $transactionId)
    {
        $this->payment = $payment;
        $this->transactionId = $transactionId;
    }

    public function getPayment(): PaymentInterface
    {
        return $this->payment;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }
}
