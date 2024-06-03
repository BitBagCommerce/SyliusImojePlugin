<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\PaymentMethod;

/**
 * @psalm-suppress MissingConstructor
 */
final class ServiceModel implements ServiceModelInterface
{
    private string $id;

    private int $created;

    private bool $isActive;

    /** @var PaymentMethodModel[] */
    private array $paymentMethods;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getCreated(): int
    {
        return $this->created;
    }

    public function setCreated(int $created): void
    {
        $this->created = $created;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return PaymentMethodModel[]
     */
    public function getPaymentMethods(): array
    {
        return $this->paymentMethods;
    }

    /**
     * @param PaymentMethodModel[] $paymentMethods
     */
    public function setPaymentMethods(array $paymentMethods): void
    {
        $this->paymentMethods = $paymentMethods;
    }
}
