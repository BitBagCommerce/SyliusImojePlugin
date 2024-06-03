<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\PaymentMethod;

interface ServiceModelInterface
{
    public function getId(): string;

    public function setId(string $id): void;

    public function getCreated(): int;

    public function setCreated(int $created): void;

    public function isActive(): bool;

    public function setIsActive(bool $isActive): void;

    public function getPaymentMethods(): array;

    /**
     * @param PaymentMethodModel[] $paymentMethods
     */
    public function setPaymentMethods(array $paymentMethods): void;
}
