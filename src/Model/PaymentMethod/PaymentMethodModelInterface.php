<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model\PaymentMethod;

interface PaymentMethodModelInterface
{
    public function getPaymentMethod(): string;

    public function getPaymentMethodCode(): string;

    public function isActive(): bool;

    public function isOnline(): bool;

    public function getDescription(): string;

    public function getCurrency(): string;

    public function getTransactionLimits(): TransactionLimits;

    public function setPaymentMethod(string $paymentMethod): void;

    public function setPaymentMethodCode(string $paymentMethodCode): void;

    public function setIsActive(bool $active): void;

    public function setIsOnline(bool $online): void;

    public function setDescription(string $description): void;

    public function setCurrency(string $currency): void;

    public function setTransactionLimits(TransactionLimits $transactionLimits): void;
}
