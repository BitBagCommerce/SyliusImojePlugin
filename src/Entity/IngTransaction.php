<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Entity;

use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class IngTransaction implements IngTransactionInterface, ResourceInterface, TimestampableInterface
{
    use TimestampableTrait;

    protected int $id;

    protected ?string $transactionId;

    protected ?PaymentInterface $payment;

    protected ?string $paymentUrl;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function getPayment(): ?PaymentInterface
    {
        return $this->payment;
    }

    public function setPayment(?PaymentInterface $payment): void
    {
        $this->payment = $payment;
    }

    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
    }

    public function setPaymentUrl(?string $paymentUrl): void
    {
        $this->paymentUrl = $paymentUrl;
    }
}
