<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\PaymentMethod;

/**
 * @psalm-suppress MissingConstructor
 */
final class PaymentMethodModel implements PaymentMethodModelInterface
{
    private string $paymentMethod;

    private string $paymentMethodCode;

    private bool $isActive;

    private bool $isOnline;

    private string $description;

    private string $currency;

    private TransactionLimits $transactionLimits;

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getPaymentMethodCode(): string
    {
        return $this->paymentMethodCode;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function isOnline(): bool
    {
        return $this->isOnline;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTransactionLimits(): TransactionLimits
    {
        return $this->transactionLimits;
    }

    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function setPaymentMethodCode(string $paymentMethodCode): void
    {
        $this->paymentMethodCode = $paymentMethodCode;
    }

    public function setIsActive(bool $active): void
    {
        $this->isActive = $active;
    }

    public function setIsOnline(bool $online): void
    {
        $this->isOnline = $online;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function setTransactionLimits(TransactionLimits $transactionLimits): void
    {
        $this->transactionLimits = $transactionLimits;
    }
}
