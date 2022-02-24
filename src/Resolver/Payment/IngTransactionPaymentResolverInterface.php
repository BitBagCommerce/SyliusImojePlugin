<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Payment;

use Sylius\Component\Core\Model\PaymentInterface;

interface IngTransactionPaymentResolverInterface
{
    public function resolve(string $transactionId): PaymentInterface;
}
