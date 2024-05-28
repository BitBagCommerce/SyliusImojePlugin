<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\PaymentMethod;

interface TransactionLimitInterface
{
    public function getType(): string;

    public function getValue(): int;

    public function setType(string $type): void;

    public function setValue(int $value): void;
}
