<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Model\PaymentMethod;

interface TransactionLimitsInterface
{
    public function getMinTransaction(): TransactionLimit;

    public function getMaxTransaction(): TransactionLimit;

    public function setMinTransaction(TransactionLimit $minTransaction): void;

    public function setMaxTransaction(TransactionLimit $maxTransaction): void;
}
