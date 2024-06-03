<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\PaymentMethod;

/**
 * @psalm-suppress MissingConstructor
 */
final class TransactionLimit implements TransactionLimitInterface
{
    private string $type;

    private int $value;

    public function __construct(string $type, int $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }
}
