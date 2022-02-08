<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Transaction;

use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface IngTransactionFactoryInterface
{
    public function createForPayment(PaymentInterface $payment, string $transactionId): IngTransactionInterface;

    public function createNew(): IngTransactionInterface;
}
