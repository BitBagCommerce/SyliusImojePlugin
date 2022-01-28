<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model;

interface TransactionModelInterface
{
    public const POST_METHOD = 'POST';

    public function getType(): string;

    public function getServiceId(): string;

    public function getAmount(): int;

    public function getCurrency(): string;

    public function getTitle(): ?string;

    public function getOrderId(): string;

    public function getPaymentMethod(): string;

    public function getPaymentMethodCode(): string;

    public function getSuccessReturnUrl(): string;

    public function getFailureReturnUrl(): string;

    public function getCustomer(): CustomerModelInterface;

    public function getBilling(): ?BillingModelInterface;

    public function getShipping(): ?ShippingModelInterface;
}
