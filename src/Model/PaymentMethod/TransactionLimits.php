<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model\PaymentMethod;

/**
 * @psalm-suppress MissingConstructor
 */
final class TransactionLimits implements TransactionLimitsInterface
{
    private TransactionLimit $minTransaction;

    private TransactionLimit $maxTransaction;

    public function getMinTransaction(): TransactionLimit
    {
        return $this->minTransaction;
    }

    public function getMaxTransaction(): TransactionLimit
    {
        return $this->maxTransaction;
    }

    public function setMinTransaction(TransactionLimit $minTransaction): void
    {
        $this->minTransaction = $minTransaction;
    }

    public function setMaxTransaction(TransactionLimit $maxTransaction): void
    {
        $this->maxTransaction = $maxTransaction;
    }
}
